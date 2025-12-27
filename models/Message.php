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

    public static function send(int $listingId, string $senderEmail, string $message, ?string $senderName = null): int|string
    {
        return self::create([
            'listing_id' => $listingId,
            'sender_email' => $senderEmail,
            'sender_name' => $senderName,
            'message' => $message
        ]);
    }
}
