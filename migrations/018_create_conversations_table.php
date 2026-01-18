<?php

/**
 * Migration: Create conversations table for message threading
 *
 * This table groups messages into conversations between users about specific listings.
 * Each conversation has two participants: user1 (listing owner) and user2 (inquirer).
 */

return [
    'up' => function($conn) {
        $conn->exec("
            CREATE TABLE conversations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                listing_id INTEGER NOT NULL,
                user1_id INTEGER NOT NULL,
                user2_id INTEGER,
                user2_email VARCHAR(100),
                user2_name VARCHAR(100),
                last_message_at DATETIME,
                user1_unread_count INTEGER DEFAULT 0,
                user2_unread_count INTEGER DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE,
                FOREIGN KEY (user1_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (user2_id) REFERENCES users(id) ON DELETE SET NULL
            )
        ");

        // Create indexes for better query performance
        $conn->exec("CREATE INDEX idx_conversations_user1 ON conversations(user1_id)");
        $conn->exec("CREATE INDEX idx_conversations_user2 ON conversations(user2_id)");
        $conn->exec("CREATE INDEX idx_conversations_listing ON conversations(listing_id)");
        $conn->exec("CREATE INDEX idx_conversations_last_message ON conversations(last_message_at DESC)");

        // Create composite index for finding existing conversations
        $conn->exec("CREATE INDEX idx_conversations_lookup ON conversations(listing_id, user1_id, user2_id)");
    },

    'down' => function($conn) {
        $conn->exec("DROP INDEX IF EXISTS idx_conversations_lookup");
        $conn->exec("DROP INDEX IF EXISTS idx_conversations_last_message");
        $conn->exec("DROP INDEX IF EXISTS idx_conversations_listing");
        $conn->exec("DROP INDEX IF EXISTS idx_conversations_user2");
        $conn->exec("DROP INDEX IF EXISTS idx_conversations_user1");
        $conn->exec("DROP TABLE IF EXISTS conversations");
    }
];
