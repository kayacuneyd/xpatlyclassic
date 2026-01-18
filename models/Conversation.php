<?php

namespace Models;

use Core\Database;
use PDO;

class Conversation extends Model
{
    protected static string $table = 'conversations';

    /**
     * Get conversations where user is the listing owner (received inquiries)
     */
    public static function getReceivedByUser(int $userId, int $page = 1, int $perPage = 10): array
    {
        $db = new Database();
        $conn = $db->getPDO();
        $offset = ($page - 1) * $perPage;

        $stmt = $conn->prepare("
            SELECT
                c.*,
                l.id as listing_id,
                l.title as listing_title,
                l.price as listing_price,
                (SELECT filename FROM listing_images WHERE listing_id = l.id ORDER BY is_primary DESC, sort_order ASC, id ASC LIMIT 1) as listing_image,
                COALESCE(u.full_name, c.user2_name) as other_person_name,
                COALESCE(u.email, c.user2_email) as other_person_email,
                c.user1_unread_count as unread,
                (
                    SELECT message
                    FROM messages
                    WHERE conversation_id = c.id
                    ORDER BY created_at DESC
                    LIMIT 1
                ) as last_message_text
            FROM conversations c
            INNER JOIN listings l ON c.listing_id = l.id
            LEFT JOIN users u ON c.user2_id = u.id
            WHERE c.user1_id = ?
            ORDER BY c.last_message_at DESC
            LIMIT ? OFFSET ?
        ");

        $stmt->execute([$userId, $perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get conversations where user sent the inquiry (sent messages)
     */
    public static function getSentByUser(int $userId, int $page = 1, int $perPage = 10): array
    {
        $db = new Database();
        $conn = $db->getPDO();
        $offset = ($page - 1) * $perPage;

        $stmt = $conn->prepare("
            SELECT
                c.*,
                l.id as listing_id,
                l.title as listing_title,
                l.price as listing_price,
                (SELECT filename FROM listing_images WHERE listing_id = l.id ORDER BY is_primary DESC, sort_order ASC, id ASC LIMIT 1) as listing_image,
                u.full_name as other_person_name,
                u.email as other_person_email,
                c.user2_unread_count as unread,
                (
                    SELECT message
                    FROM messages
                    WHERE conversation_id = c.id
                    ORDER BY created_at DESC
                    LIMIT 1
                ) as last_message_text
            FROM conversations c
            INNER JOIN listings l ON c.listing_id = l.id
            INNER JOIN users u ON c.user1_id = u.id
            WHERE c.user2_id = ? OR (c.user2_id IS NULL AND c.user2_email = (SELECT email FROM users WHERE id = ?))
            ORDER BY c.last_message_at DESC
            LIMIT ? OFFSET ?
        ");

        $stmt->execute([$userId, $userId, $perPage, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Count total received conversations
     */
    public static function countReceivedByUser(int $userId): int
    {
        $db = new Database();
        $conn = $db->getPDO();

        $stmt = $conn->prepare("SELECT COUNT(*) FROM conversations WHERE user1_id = ?");
        $stmt->execute([$userId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Count total sent conversations
     */
    public static function countSentByUser(int $userId): int
    {
        $db = new Database();
        $conn = $db->getPDO();

        $stmt = $conn->prepare("
            SELECT COUNT(*)
            FROM conversations
            WHERE user2_id = ? OR (user2_id IS NULL AND user2_email = (SELECT email FROM users WHERE id = ?))
        ");
        $stmt->execute([$userId, $userId]);

        return (int) $stmt->fetchColumn();
    }

    /**
     * Get a single conversation by ID with full details
     */
    public static function getById(int $id): ?array
    {
        $db = new Database();
        $conn = $db->getPDO();

        $stmt = $conn->prepare("
            SELECT
                c.*,
                l.id as listing_id,
                l.title as listing_title,
                l.price as listing_price,
                (SELECT filename FROM listing_images WHERE listing_id = l.id ORDER BY is_primary DESC, sort_order ASC, id ASC LIMIT 1) as listing_image,
                l.user_id as listing_owner_id,
                u1.full_name as user1_name,
                u1.email as user1_email,
                COALESCE(u2.full_name, c.user2_name) as user2_name,
                COALESCE(u2.email, c.user2_email) as user2_email
            FROM conversations c
            INNER JOIN listings l ON c.listing_id = l.id
            INNER JOIN users u1 ON c.user1_id = u1.id
            LEFT JOIN users u2 ON c.user2_id = u2.id
            WHERE c.id = ?
        ");

        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return $result ?: null;
    }

    /**
     * Find existing conversation or create a new one
     */
    public static function findOrCreate(
        int $listingId,
        int $user1Id,
        ?int $user2Id,
        ?string $user2Email,
        ?string $user2Name
    ): int {
        $db = new Database();
        $conn = $db->getPDO();

        // Try to find existing conversation
        $stmt = $conn->prepare("
            SELECT id FROM conversations
            WHERE listing_id = ?
            AND user1_id = ?
            AND (
                (user2_id = ? AND user2_id IS NOT NULL)
                OR (user2_email = ? AND user2_id IS NULL)
            )
            LIMIT 1
        ");

        $stmt->execute([$listingId, $user1Id, $user2Id, $user2Email]);
        $existing = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            return (int) $existing['id'];
        }

        // Create new conversation
        $stmt = $conn->prepare("
            INSERT INTO conversations (
                listing_id, user1_id, user2_id, user2_email, user2_name,
                last_message_at, created_at, updated_at
            ) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");

        $stmt->execute([$listingId, $user1Id, $user2Id, $user2Email, $user2Name]);

        return (int) $conn->lastInsertId();
    }

    /**
     * Get unread conversation count for a user
     *
     * @param string $type 'received' or 'sent'
     */
    public static function getUnreadCount(int $userId, string $type = 'received'): int
    {
        $db = new Database();
        $conn = $db->getPDO();

        if ($type === 'received') {
            $stmt = $conn->prepare("
                SELECT SUM(user1_unread_count)
                FROM conversations
                WHERE user1_id = ?
            ");
            $stmt->execute([$userId]);
        } else {
            $stmt = $conn->prepare("
                SELECT SUM(user2_unread_count)
                FROM conversations
                WHERE user2_id = ? OR (user2_id IS NULL AND user2_email = (SELECT email FROM users WHERE id = ?))
            ");
            $stmt->execute([$userId, $userId]);
        }

        return (int) $stmt->fetchColumn();
    }

    /**
     * Mark conversation as read for a specific user
     */
    public static function markAsRead(int $conversationId, int $userId): bool
    {
        $db = new Database();
        $conn = $db->getPDO();

        // Determine if user is user1 or user2
        $stmt = $conn->prepare("SELECT user1_id, user2_id FROM conversations WHERE id = ?");
        $stmt->execute([$conversationId]);
        $conv = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$conv) {
            return false;
        }

        if ($conv['user1_id'] == $userId) {
            // Reset user1's unread count
            $stmt = $conn->prepare("UPDATE conversations SET user1_unread_count = 0 WHERE id = ?");
            $stmt->execute([$conversationId]);
        } elseif ($conv['user2_id'] == $userId) {
            // Reset user2's unread count
            $stmt = $conn->prepare("UPDATE conversations SET user2_unread_count = 0 WHERE id = ?");
            $stmt->execute([$conversationId]);
        }

        return true;
    }

    /**
     * Increment unread count for the recipient
     *
     * @param string $recipientType 'user1' or 'user2'
     */
    public static function incrementUnread(int $conversationId, string $recipientType): void
    {
        $db = new Database();
        $conn = $db->getPDO();

        $column = $recipientType === 'user1' ? 'user1_unread_count' : 'user2_unread_count';

        $stmt = $conn->prepare("
            UPDATE conversations
            SET {$column} = {$column} + 1,
                last_message_at = CURRENT_TIMESTAMP,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $stmt->execute([$conversationId]);
    }

    /**
     * Update last message timestamp
     */
    public static function updateLastMessageTime(int $conversationId): void
    {
        $db = new Database();
        $conn = $db->getPDO();

        $stmt = $conn->prepare("
            UPDATE conversations
            SET last_message_at = CURRENT_TIMESTAMP,
                updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");

        $stmt->execute([$conversationId]);
    }
}
