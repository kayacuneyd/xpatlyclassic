<?php
/**
 * Migration: Create blog_comments table for blog comment system
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

// Create blog_comments table
$conn->exec("
    CREATE TABLE IF NOT EXISTS blog_comments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        post_id INTEGER NOT NULL,
        user_id INTEGER NOT NULL,
        content TEXT NOT NULL,
        status VARCHAR(20) DEFAULT 'pending',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,

        FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )
");

// Create indexes for faster queries
$conn->exec("CREATE INDEX IF NOT EXISTS idx_blog_comments_post_id ON blog_comments(post_id)");
$conn->exec("CREATE INDEX IF NOT EXISTS idx_blog_comments_user_id ON blog_comments(user_id)");
$conn->exec("CREATE INDEX IF NOT EXISTS idx_blog_comments_status ON blog_comments(status)");

echo "Blog comments table created successfully.\n";
