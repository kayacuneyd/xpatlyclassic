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
}
