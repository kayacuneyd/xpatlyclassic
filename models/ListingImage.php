<?php

namespace Models;

class ListingImage extends Model
{
    protected static string $table = 'listing_images';

    public static function getByListing(int $listingId): array
    {
        $sql = "SELECT * FROM " . self::$table . "
                WHERE listing_id = ?
                ORDER BY sort_order ASC, created_at ASC";

        $result = self::query($sql, [$listingId]);
        return $result->fetchAll();
    }

    public static function getPrimaryImage(int $listingId): ?array
    {
        return self::first(['listing_id' => $listingId, 'is_primary' => 1]);
    }

    public static function setPrimary(int $id, int $listingId): void
    {
        // First, unset all primary images for this listing
        $sql = "UPDATE " . self::$table . " SET is_primary = 0 WHERE listing_id = ?";
        self::query($sql, [$listingId]);

        // Then set the new primary
        self::update($id, ['is_primary' => 1]);
    }

    public static function updateOrder(array $imageIds): void
    {
        foreach ($imageIds as $order => $imageId) {
            self::update($imageId, ['sort_order' => $order]);
        }
    }

    public static function deleteByListing(int $listingId): void
    {
        // Get all images first to delete files
        $images = self::getByListing($listingId);

        foreach ($images as $image) {
            // Delete actual files
            $filepath = __DIR__ . '/../public/uploads/listings/' . $image['filename'];
            if (file_exists($filepath)) {
                unlink($filepath);
            }

            // Delete thumbnail
            $thumbPath = str_replace('.jpg', '_thumb.jpg', $filepath);
            if (file_exists($thumbPath)) {
                unlink($thumbPath);
            }
        }

        // Delete database records
        $db = self::getDB();
        $db->delete(self::$table, ['listing_id' => $listingId]);
    }

    public static function countByListing(int $listingId): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE listing_id = ?";
        $result = self::query($sql, [$listingId]);
        $row = $result->fetch();
        return (int)($row['count'] ?? 0);
    }
}
