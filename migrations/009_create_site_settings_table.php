<?php

/**
 * Migration: Create site_settings table
 * Stores site-wide configuration like logo, icon, SEO metadata
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

try {
    $db = new Database();
    $conn = $db->getPDO();

    // Check if MySQL or SQLite
    $config = require __DIR__ . '/../config/database.php';
    $isMysql = $config['connection'] === 'mysql';

    // Create site_settings table (compatible with both MySQL and SQLite)
    if ($isMysql) {
        $sql = "
        CREATE TABLE IF NOT EXISTS site_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            setting_key VARCHAR(100) UNIQUE NOT NULL,
            setting_value TEXT,
            setting_type ENUM('text', 'textarea', 'image', 'url') DEFAULT 'text',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX idx_setting_key (setting_key)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ";
    } else {
        $sql = "
        CREATE TABLE IF NOT EXISTS site_settings (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            setting_key TEXT UNIQUE NOT NULL,
            setting_value TEXT,
            setting_type TEXT DEFAULT 'text',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );
        ";
    }

    $conn->exec($sql);
    echo "✓ Created site_settings table\n";

    // Insert default settings
    $defaults = [
        ['site_name', 'Xpatly', 'text'],
        ['site_tagline', 'Expat-Friendly Housing in Estonia', 'text'],
        ['site_logo', '', 'image'],
        ['site_icon', '', 'image'],
        ['meta_description', 'Find expat-friendly housing in Estonia. Zero discrimination, verified listings, direct contact with landlords.', 'textarea'],
        ['meta_keywords', 'expat housing estonia, rent apartment tallinn, expat friendly, housing discrimination free', 'textarea'],
        ['contact_email', 'hello@xpatly.com', 'text'],
        ['contact_phone', '+372 0000 0000', 'text'],
    ];

    $stmt = $conn->prepare("
        INSERT OR IGNORE INTO site_settings (setting_key, setting_value, setting_type)
        VALUES (?, ?, ?)
    ");

    foreach ($defaults as $default) {
        $stmt->execute($default);
    }

    echo "✓ Inserted default site settings\n";
    echo "Migration 009 completed successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
