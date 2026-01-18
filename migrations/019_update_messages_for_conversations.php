<?php

/**
 * Migration: Update messages table to support conversations
 *
 * Adds conversation_id and sender_type fields to link messages to conversations
 * and track which participant sent each message.
 */

return [
    'up' => function($conn) {
        // Add conversation_id column
        $conn->exec("ALTER TABLE messages ADD COLUMN conversation_id INTEGER");

        // Add sender_type column ('user1' = listing owner, 'user2' = inquirer)
        $conn->exec("ALTER TABLE messages ADD COLUMN sender_type VARCHAR(20) DEFAULT 'user2'");

        // Create index for better query performance
        $conn->exec("CREATE INDEX idx_messages_conversation ON messages(conversation_id)");

        // Add foreign key constraint (SQLite 3.6.19+)
        // Note: SQLite doesn't support adding FK constraints to existing tables,
        // so we rely on application-level integrity
    },

    'down' => function($conn) {
        // SQLite doesn't support DROP COLUMN, so we would need to recreate the table
        // For now, we'll just drop the index
        $conn->exec("DROP INDEX IF EXISTS idx_messages_conversation");

        // Note: conversation_id and sender_type columns will remain but be unused
        // Full rollback would require table recreation with data migration
    }
];
