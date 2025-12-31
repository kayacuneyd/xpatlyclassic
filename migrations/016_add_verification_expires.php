<?php
/**
 * Migration: Add verification_expires column to users table
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

echo "Adding verification_expires column to users table...\n";

$conn->exec("ALTER TABLE users ADD COLUMN verification_expires DATETIME NULL");

echo "✅ Migration 016 completed: verification_expires column added\n";
