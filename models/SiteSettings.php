<?php

namespace Models;

use Core\Database;
use PDO;

class SiteSettings
{
    private static ?array $cache = null;

    /**
     * Get all settings as key-value array
     */
    public static function getAll(): array
    {
        if (self::$cache !== null) {
            return self::$cache;
        }

        $db = new Database();
        $stmt = $db->getPDO()->query("SELECT setting_key, setting_value FROM site_settings");

        $settings = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        self::$cache = $settings;
        return $settings;
    }

    /**
     * Get single setting value
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        $all = self::getAll();
        return $all[$key] ?? $default;
    }

    /**
     * Set/update a setting
     */
    public static function set(string $key, string $value, string $type = 'text'): bool
    {
        $db = new Database();
        $conn = $db->getPDO();

        // Check if MySQL or SQLite
        $config = require __DIR__ . '/../config/database.php';
        $isMysql = $config['connection'] === 'mysql';

        if ($isMysql) {
            $stmt = $conn->prepare("
                INSERT INTO site_settings (setting_key, setting_value, setting_type)
                VALUES (?, ?, ?)
                ON DUPLICATE KEY UPDATE setting_value = ?, updated_at = CURRENT_TIMESTAMP
            ");
            $result = $stmt->execute([$key, $value, $type, $value]);
        } else {
            // SQLite: Use INSERT OR REPLACE
            $stmt = $conn->prepare("
                INSERT OR REPLACE INTO site_settings (setting_key, setting_value, setting_type, updated_at)
                VALUES (?, ?, ?, CURRENT_TIMESTAMP)
            ");
            $result = $stmt->execute([$key, $value, $type]);
        }

        // Clear cache
        self::$cache = null;

        return $result;
    }

    /**
     * Get logo path (with fallback)
     */
    public static function getLogo(): ?string
    {
        $logo = self::get('site_logo');
        return $logo ?: null;
    }

    /**
     * Get icon/favicon path
     */
    public static function getIcon(): ?string
    {
        $icon = self::get('site_icon');
        return $icon ?: null;
    }

    /**
     * Get site name
     */
    public static function getSiteName(): string
    {
        return self::get('site_name', 'Xpatly');
    }

    /**
     * Get meta description
     */
    public static function getMetaDescription(): string
    {
        return self::get('meta_description', '');
    }

    /**
     * Get meta keywords
     */
    public static function getMetaKeywords(): string
    {
        return self::get('meta_keywords', '');
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        self::$cache = null;
    }

    /**
     * Get all settings with metadata (for admin)
     */
    public static function getAllWithMeta(): array
    {
        $db = new Database();
        $stmt = $db->getPDO()->query("
            SELECT setting_key, setting_value, setting_type, updated_at 
            FROM site_settings 
            ORDER BY setting_key
        ");

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
