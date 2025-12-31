<?php
/**
 * Migration: Add updated_at column to admin_logs table
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

echo "Adding updated_at column to admin_logs table...\n";

$conn->exec("ALTER TABLE admin_logs ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP");

echo "✅ Migration 017 completed: updated_at column added to admin_logs\n";
