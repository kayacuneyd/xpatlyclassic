<?php
/**
 * Migration: Add Cloudflare R2 storage settings
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

// Check if settings already exist
$existing = $conn->prepare("SELECT COUNT(*) as count FROM site_settings WHERE setting_key LIKE 'r2_%'");
$existing->execute();
$row = $existing->fetch();

if ($row['count'] == 0) {
    $settings = [
        ['r2_access_key_id', '', 'text'],
        ['r2_secret_access_key', '', 'text'],
        ['r2_bucket_name', '', 'text'],
        ['r2_endpoint', '', 'text'],
        ['r2_public_url', '', 'text'],
    ];

    $stmt = $conn->prepare("INSERT INTO site_settings (setting_key, setting_value, setting_type) VALUES (?, ?, ?)");

    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }

    echo "R2 storage settings added successfully.\n";
} else {
    echo "R2 storage settings already exist.\n";
}
