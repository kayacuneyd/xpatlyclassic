<?php

namespace Models;

class Favorite extends Model
{
    protected static string $table = 'favorites';

    public static function getByUser(int $userId): array
    {
        $sql = "SELECT f.*, l.*, u.full_name as owner_name,
                (SELECT filename FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM " . self::$table . " f
                INNER JOIN listings l ON f.listing_id = l.id
                LEFT JOIN users u ON l.user_id = u.id
                WHERE f.user_id = ?
                ORDER BY f.created_at DESC";

        $result = self::query($sql, [$userId]);
        $rows = $result->fetchAll();
        return array_map(['Models\\Listing', 'applyLocale'], $rows);
    }

    public static function toggle(int $userId, int $listingId): bool
    {
        $existing = self::first(['user_id' => $userId, 'listing_id' => $listingId]);

        if ($existing) {
            // Remove favorite
            self::delete($existing['id']);
            return false;
        } else {
            // Add favorite
            self::create([
                'user_id' => $userId,
                'listing_id' => $listingId
            ]);
            return true;
        }
    }

    public static function isFavorited(int $userId, int $listingId): bool
    {
        return self::first(['user_id' => $userId, 'listing_id' => $listingId]) !== null;
    }

    public static function count(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE user_id = ?";
        $result = self::query($sql, [$userId]);
        $row = $result->fetch();
        return (int)($row['count'] ?? 0);
    }
}
