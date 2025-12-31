<?php
/**
 * Migration: Add sender_user_id to messages table for tracking sent messages
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

echo "Adding sender_user_id column to messages table...\n";

// Add sender_user_id column
$conn->exec("ALTER TABLE messages ADD COLUMN sender_user_id INTEGER NULL");

// Create index for performance
$conn->exec("CREATE INDEX idx_messages_sender_user_id ON messages(sender_user_id)");

// Update existing messages: match sender_email with user.email
$updatedCount = $conn->exec("
    UPDATE messages
    SET sender_user_id = (
        SELECT id FROM users WHERE users.email = messages.sender_email LIMIT 1
    )
    WHERE sender_email IN (SELECT email FROM users)
");

echo "✅ Migration 013 completed: sender_user_id column added\n";
echo "✅ Updated {$updatedCount} existing messages with user IDs\n";

