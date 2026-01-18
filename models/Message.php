<?php

namespace Models;

class Message extends Model
{
    protected static string $table = 'messages';

    public static function getByListing(int $listingId): array
    {
        $sql = "SELECT * FROM " . self::$table . "
                WHERE listing_id = ?
                ORDER BY created_at DESC";

        $result = self::query($sql, [$listingId]);
        return $result->fetchAll();
    }

    public static function getByOwner(int $userId): array
    {
        $sql = "SELECT m.*, l.title as listing_title, l.id as listing_id
                FROM " . self::$table . " m
                INNER JOIN listings l ON m.listing_id = l.id
                WHERE l.user_id = ?
                ORDER BY m.created_at DESC";

        $result = self::query($sql, [$userId]);
        return $result->fetchAll();
    }

    public static function getUnreadCount(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count
                FROM " . self::$table . " m
                INNER JOIN listings l ON m.listing_id = l.id
                WHERE l.user_id = ? AND m.read_at IS NULL";

        $result = self::query($sql, [$userId]);
        $row = $result->fetch();
        return (int)($row['count'] ?? 0);
    }

    public static function markAsRead(int $id): int
    {
        return self::update($id, ['read_at' => date('Y-m-d H:i:s')]);
    }

    public static function send(int $listingId, string $senderEmail, string $message, ?string $senderName = null, ?int $senderUserId = null): int|string
    {
        $data = [
            'listing_id' => $listingId,
            'sender_email' => $senderEmail,
            'sender_name' => $senderName,
            'message' => $message
        ];

        if ($senderUserId) {
            $data['sender_user_id'] = $senderUserId;
        }

        return self::create($data);
    }

    /**
     * Get messages sent by a user (ORANGE category - Sent Inquiries)
     */
    public static function getSentByUser(int $userId): array
    {
        $sql = "SELECT m.*,
                       l.title as listing_title,
                       l.price as listing_price,
                       u.full_name as owner_name,
                       u.email as owner_email
                FROM " . self::$table . " m
                JOIN listings l ON m.listing_id = l.id
                JOIN users u ON l.user_id = u.id
                WHERE m.sender_user_id = ?
                ORDER BY m.created_at DESC";

        $result = self::query($sql, [$userId]);
        return $result->fetchAll();
    }

    /**
     * Get messages received by a user (BLUE category - Incoming Inquiries)
     * Alias for getByOwner() for clarity
     */
    public static function getReceivedByUser(int $userId): array
    {
        return self::getByOwner($userId);
    }

    /**
     * Get count of unread sent messages (placeholder for future reply tracking)
     */
    public static function getUnreadSentCount(int $userId): int
    {
        // TODO: Implement reply tracking in the future
        return 0;
    }

    /**
     * Get all messages in a conversation, ordered chronologically
     */
    public static function getByConversation(int $conversationId): array
    {
        $sql = "SELECT m.*,
                       CASE
                           WHEN m.sender_type = 'user1' THEN c.user1_id
                           ELSE c.user2_id
                       END as sender_id,
                       CASE
                           WHEN m.sender_type = 'user1' THEN (SELECT full_name FROM users WHERE id = c.user1_id)
                           ELSE COALESCE((SELECT full_name FROM users WHERE id = c.user2_id), c.user2_name)
                       END as sender_name
                FROM " . self::$table . " m
                INNER JOIN conversations c ON m.conversation_id = c.id
                WHERE m.conversation_id = ?
                ORDER BY m.created_at ASC";

        $result = self::query($sql, [$conversationId]);
        return $result->fetchAll();
    }

    /**
     * Send a reply in an existing conversation
     */
    public static function sendInConversation(
        int $conversationId,
        string $message,
        int $senderId,
        string $senderType,
        string $senderEmail,
        ?string $senderName = null
    ): int {
        // Get conversation to retrieve listing_id BEFORE creating the message
        $conv = \Models\Conversation::getById($conversationId);
        if (!$conv) {
            throw new \Exception('Conversation not found');
        }

        // Create the message with the correct listing_id
        $messageId = self::create([
            'conversation_id' => $conversationId,
            'message' => $message,
            'sender_type' => $senderType,
            'sender_email' => $senderEmail,
            'sender_name' => $senderName,
            'listing_id' => $conv['listing_id']
        ]);

        // Increment unread count for recipient
        $recipientType = $senderType === 'user1' ? 'user2' : 'user1';
        \Models\Conversation::incrementUnread($conversationId, $recipientType);

        // Send email notification
        self::sendEmailNotification($conversationId, $conv, $message, $senderType);

        return $messageId;
    }

    /**
     * Send email notification for new message
     */
    private static function sendEmailNotification(
        int $conversationId,
        array $conversation,
        string $message,
        string $senderType
    ): void {
        // Determine recipient
        if ($senderType === 'user1') {
            // Owner replied, notify inquirer
            $recipientEmail = $conversation['user2_email'];
            $recipientName = $conversation['user2_name'];
            $senderName = $conversation['user1_name'];
        } else {
            // Inquirer replied, notify owner
            $recipientEmail = $conversation['user1_email'];
            $recipientName = $conversation['user1_name'];
            $senderName = $conversation['user2_name'];
        }

        if (!$recipientEmail) {
            return;
        }

        // Check if email is enabled
        if (!\Core\Email::isEnabled()) {
            return;
        }

        // Render email template
        ob_start();
        $listingTitle = $conversation['listing_title'];
        require __DIR__ . '/../emails/new-message.php';
        $html = ob_get_clean();

        // Send email
        \Core\Email::send(
            $recipientEmail,
            'New message on Xpatly',
            $html
        );
    }
}
