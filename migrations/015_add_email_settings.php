<?php
/**
 * Migration: Add email settings for Resend API
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

echo "Adding email settings to site_settings table...\n";

// Email settings for Resend API
$settings = [
    ['resend_api_key', '', 'text'],
    ['email_from_address', 'noreply@xpatly.com', 'text'],
    ['email_from_name', 'Xpatly', 'text'],
    ['email_enabled', '0', 'text'],
];

$stmt = $conn->prepare("
    INSERT OR IGNORE INTO site_settings (setting_key, setting_value, setting_type)
    VALUES (?, ?, ?)
");

foreach ($settings as $setting) {
    $stmt->execute($setting);
}

echo "✅ Migration 015 completed: Email settings added\n";
