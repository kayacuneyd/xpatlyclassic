<?php

namespace Models;

use Core\Translation;

class Listing extends Model
{
    protected static string $table = 'listings';

    public static function getWithOwner(int $id): ?array
    {
        $sql = "SELECT l.*, u.full_name as owner_name, u.email as owner_email, u.phone as owner_phone
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.user_id = u.id
                WHERE l.id = ?";

        $result = self::query($sql, [$id]);
        return $result->fetch() ?: null;
    }

    public static function getActive(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT l.*, u.full_name as owner_name,
                (SELECT filename FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.user_id = u.id
                WHERE l.status = 'active' AND l.is_available = 1";

        $params = [];

        // Apply filters
        if (!empty($filters['region'])) {
            $sql .= " AND LOWER(l.region) = LOWER(?)";
            $params[] = $filters['region'];
        }

        if (!empty($filters['settlement'])) {
            $sql .= " AND LOWER(l.settlement) = LOWER(?)";
            $params[] = $filters['settlement'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(l.category) = LOWER(?)";
            $params[] = $filters['category'];
        }

        if (!empty($filters['deal_type'])) {
            $sql .= " AND LOWER(l.deal_type) = LOWER(?)";
            $params[] = $filters['deal_type'];
        }

        if (!empty($filters['expat_friendly'])) {
            $sql .= " AND l.expat_friendly = 1";
        }

        if (!empty($filters['pets_allowed'])) {
            $sql .= " AND l.pets_allowed = 1";
        }

        if (!empty($filters['condition'])) {
            $sql .= " AND l.condition = ?";
            $params[] = $filters['condition'];
        }

        // Rooms range
        if (isset($filters['rooms_min']) && $filters['rooms_min'] !== '') {
            $sql .= " AND l.rooms >= ?";
            $params[] = (int) $filters['rooms_min'];
        }

        if (isset($filters['rooms_max']) && $filters['rooms_max'] !== '') {
            $sql .= " AND l.rooms <= ?";
            $params[] = (int) $filters['rooms_max'];
        }

        // Price range
        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $sql .= " AND l.price >= ?";
            $params[] = (float) $filters['price_min'];
        }

        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $sql .= " AND l.price <= ?";
            $params[] = (float) $filters['price_max'];
        }

        // Area range
        if (isset($filters['area_min']) && $filters['area_min'] !== '') {
            $sql .= " AND l.area_sqm >= ?";
            $params[] = (float) $filters['area_min'];
        }

        if (isset($filters['area_max']) && $filters['area_max'] !== '') {
            $sql .= " AND l.area_sqm <= ?";
            $params[] = (float) $filters['area_max'];
        }

        // Floor range
        if (isset($filters['floor_min']) && $filters['floor_min'] !== '') {
            $sql .= " AND l.floor >= ?";
            $params[] = (int) $filters['floor_min'];
        }

        if (isset($filters['floor_max']) && $filters['floor_max'] !== '') {
            $sql .= " AND l.floor <= ?";
            $params[] = (int) $filters['floor_max'];
        }

        // Year built range
        if (isset($filters['year_min']) && $filters['year_min'] !== '') {
            $sql .= " AND l.year_built >= ?";
            $params[] = (int) $filters['year_min'];
        }

        if (isset($filters['year_max']) && $filters['year_max'] !== '') {
            $sql .= " AND l.year_built <= ?";
            $params[] = (int) $filters['year_max'];
        }

        // Energy class
        if (!empty($filters['energy_class'])) {
            $sql .= " AND UPPER(l.energy_class) = UPPER(?)";
            $params[] = $filters['energy_class'];
        }

        if (!empty($filters['q'])) {
            $sql .= " AND (LOWER(l.title) LIKE ? OR LOWER(l.description) LIKE ? OR LOWER(l.region) LIKE ? OR LOWER(l.settlement) LIKE ?)";
            $searchTerm = '%' . strtolower($filters['q']) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Extras (JSON search)
        if (!empty($filters['extras'])) {
            $extras = is_array($filters['extras']) ? $filters['extras'] : [$filters['extras']];
            foreach ($extras as $extra) {
                $sql .= " AND JSON_EXTRACT(l.extras, '$." . $extra . "') = 1";
            }
        }

        // Geographic bounds (map search)
        if (isset($filters['lat_min']) && $filters['lat_min'] !== '') {
            $sql .= " AND l.latitude >= ?";
            $params[] = (float) $filters['lat_min'];
        }

        if (isset($filters['lat_max']) && $filters['lat_max'] !== '') {
            $sql .= " AND l.latitude <= ?";
            $params[] = (float) $filters['lat_max'];
        }

        if (isset($filters['lng_min']) && $filters['lng_min'] !== '') {
            $sql .= " AND l.longitude >= ?";
            $params[] = (float) $filters['lng_min'];
        }

        if (isset($filters['lng_max']) && $filters['lng_max'] !== '') {
            $sql .= " AND l.longitude <= ?";
            $params[] = (float) $filters['lng_max'];
        }

        // Sorting
        $sql .= match ($filters['sort'] ?? 'newest') {
            'price_low' => " ORDER BY l.price ASC",
            'price_high' => " ORDER BY l.price DESC",
            'area' => " ORDER BY l.area_sqm DESC",
            'price_per_sqm' => " ORDER BY l.price_per_sqm ASC",
            'relevance' => " ORDER BY l.expat_friendly DESC, l.created_at DESC",
            default => " ORDER BY l.created_at DESC"
        };

        $sql .= " LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $result = self::query($sql, $params);
        $rows = $result->fetchAll();
        return array_map([self::class, 'applyLocale'], $rows);
    }

    public static function countActive(array $filters = []): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE status = 'active' AND is_available = 1";
        $params = [];

        // Apply same filters as getActive
        if (!empty($filters['region'])) {
            $sql .= " AND LOWER(region) = LOWER(?)";
            $params[] = $filters['region'];
        }

        if (!empty($filters['settlement'])) {
            $sql .= " AND LOWER(settlement) = LOWER(?)";
            $params[] = $filters['settlement'];
        }

        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(category) = LOWER(?)";
            $params[] = $filters['category'];
        }

        if (!empty($filters['deal_type'])) {
            $sql .= " AND LOWER(deal_type) = LOWER(?)";
            $params[] = $filters['deal_type'];
        }

        if (!empty($filters['expat_friendly'])) {
            $sql .= " AND expat_friendly = 1";
        }

        if (!empty($filters['pets_allowed'])) {
            $sql .= " AND pets_allowed = 1";
        }

        if (!empty($filters['condition'])) {
            $sql .= " AND LOWER(condition) = LOWER(?)";
            $params[] = $filters['condition'];
        }

        if (isset($filters['rooms_min']) && $filters['rooms_min'] !== '') {
            $sql .= " AND rooms >= ?";
            $params[] = (int) $filters['rooms_min'];
        }

        if (isset($filters['rooms_max']) && $filters['rooms_max'] !== '') {
            $sql .= " AND rooms <= ?";
            $params[] = (int) $filters['rooms_max'];
        }

        if (isset($filters['price_min']) && $filters['price_min'] !== '') {
            $sql .= " AND price >= ?";
            $params[] = (float) $filters['price_min'];
        }

        if (isset($filters['price_max']) && $filters['price_max'] !== '') {
            $sql .= " AND price <= ?";
            $params[] = (float) $filters['price_max'];
        }

        if (isset($filters['area_min']) && $filters['area_min'] !== '') {
            $sql .= " AND area_sqm >= ?";
            $params[] = (float) $filters['area_min'];
        }

        if (isset($filters['area_max']) && $filters['area_max'] !== '') {
            $sql .= " AND area_sqm <= ?";
            $params[] = (float) $filters['area_max'];
        }

        // Floor range
        if (isset($filters['floor_min']) && $filters['floor_min'] !== '') {
            $sql .= " AND floor >= ?";
            $params[] = (int) $filters['floor_min'];
        }

        if (isset($filters['floor_max']) && $filters['floor_max'] !== '') {
            $sql .= " AND floor <= ?";
            $params[] = (int) $filters['floor_max'];
        }

        // Year built range
        if (isset($filters['year_min']) && $filters['year_min'] !== '') {
            $sql .= " AND year_built >= ?";
            $params[] = (int) $filters['year_min'];
        }

        if (isset($filters['year_max']) && $filters['year_max'] !== '') {
            $sql .= " AND year_built <= ?";
            $params[] = (int) $filters['year_max'];
        }

        // Energy class
        if (!empty($filters['energy_class'])) {
            $sql .= " AND UPPER(energy_class) = UPPER(?)";
            $params[] = $filters['energy_class'];
        }

        // Extras (JSON search) - CRITICAL: Was completely missing
        if (!empty($filters['extras'])) {
            $extras = is_array($filters['extras']) ? $filters['extras'] : [$filters['extras']];
            foreach ($extras as $extra) {
                $sql .= " AND JSON_EXTRACT(extras, '$." . $extra . "') = 1";
            }
        }

        if (!empty($filters['q'])) {
            $sql .= " AND (LOWER(title) LIKE ? OR LOWER(description) LIKE ? OR LOWER(region) LIKE ? OR LOWER(settlement) LIKE ?)";
            $searchTerm = '%' . strtolower($filters['q']) . '%';
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }

        // Geographic bounds (map search)
        if (isset($filters['lat_min']) && $filters['lat_min'] !== '') {
            $sql .= " AND latitude >= ?";
            $params[] = (float) $filters['lat_min'];
        }

        if (isset($filters['lat_max']) && $filters['lat_max'] !== '') {
            $sql .= " AND latitude <= ?";
            $params[] = (float) $filters['lat_max'];
        }

        if (isset($filters['lng_min']) && $filters['lng_min'] !== '') {
            $sql .= " AND longitude >= ?";
            $params[] = (float) $filters['lng_min'];
        }

        if (isset($filters['lng_max']) && $filters['lng_max'] !== '') {
            $sql .= " AND longitude <= ?";
            $params[] = (float) $filters['lng_max'];
        }

        $result = self::query($sql, $params);
        $row = $result->fetch();
        return (int) ($row['count'] ?? 0);
    }

    public static function getByUser(int $userId, ?string $status = null): array
    {
        $sql = "SELECT l.*,
                (SELECT filename FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM " . self::$table . " l
                WHERE l.user_id = ?";

        $params = [$userId];

        if ($status) {
            $sql .= " AND l.status = ?";
            $params[] = $status;
        }

        $sql .= " ORDER BY l.created_at DESC";

        $result = self::query($sql, $params);
        $rows = $result->fetchAll();
        return array_map([self::class, 'applyLocale'], $rows);
    }

    public static function checkDuplicate(string $address, int $userId, ?int $excludeId = null): ?array
    {
        $sql = "SELECT * FROM " . self::$table . " WHERE address = ? AND user_id = ?";
        $params = [$address, $userId];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = self::query($sql, $params);
        return $result->fetch() ?: null;
    }

    public static function getPending(): array
    {
        $sql = "SELECT l.*, u.full_name as owner_name,
                (SELECT filename FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.user_id = u.id
                WHERE l.status = 'pending'
                ORDER BY l.created_at ASC";

        $result = self::query($sql);
        $rows = $result->fetchAll();
        return array_map([self::class, 'applyLocale'], $rows);
    }

    public static function adminSearch(string $query = '', array $filters = []): array
    {
        $sql = "SELECT l.*, u.full_name as owner_name, u.email as owner_email,
                (SELECT filename FROM listing_images WHERE listing_id = l.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.user_id = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($query)) {
            $sql .= " AND (l.id = ? OR l.title LIKE ? OR u.full_name LIKE ?)";
            $params[] = is_numeric($query) ? (int) $query : 0;
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }

        if (!empty($filters['status'])) {
            $sql .= " AND l.status = ?";
            $params[] = $filters['status'];
        }

        if (isset($filters['expat_friendly'])) {
            $sql .= " AND l.expat_friendly = ?";
            $params[] = $filters['expat_friendly'];
        }

        $sql .= " ORDER BY l.created_at DESC";

        $result = self::query($sql, $params);
        return $result->fetchAll();
    }

    public static function changeStatus(int $id, string $status): int
    {
        return self::update($id, ['status' => $status]);
    }

    public static function toggleAvailability(int $id): int
    {
        $listing = self::find($id);
        $newStatus = $listing['is_available'] ? 0 : 1;

        return self::update($id, ['is_available' => $newStatus]);
    }

    public static function incrementViews(int $id): void
    {
        $sql = "UPDATE " . self::$table . " SET views = views + 1 WHERE id = ?";
        self::query($sql, [$id]);
    }

    public static function getStats(): array
    {
        $sql = "SELECT
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'paused' THEN 1 ELSE 0 END) as paused,
                SUM(CASE WHEN expat_friendly = 1 THEN 1 ELSE 0 END) as expat_friendly
                FROM " . self::$table;

        $result = self::query($sql);
        return $result->fetch();
    }

    public static function applyLocale(array $listing): array
    {
        $locale = Translation::getLocale();
        $titleKey = "title_{$locale}";
        $descKey = "description_{$locale}";

        if (!empty($listing[$titleKey])) {
            $listing['title'] = $listing[$titleKey];
        }

        if (!empty($listing[$descKey])) {
            $listing['description'] = $listing[$descKey];
        }

        return $listing;
    }
}
