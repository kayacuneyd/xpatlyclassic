<?php
/**
 * Migration: Create blog_posts table for multilingual blog module
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

// Create blog_posts table
$conn->exec("
    CREATE TABLE IF NOT EXISTS blog_posts (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        slug VARCHAR(255) NOT NULL UNIQUE,
        status VARCHAR(20) DEFAULT 'draft',
        featured_image VARCHAR(500),
        author_id INTEGER,
        
        -- English (primary)
        title_en VARCHAR(255) NOT NULL,
        content_en TEXT,
        meta_title_en VARCHAR(255),
        meta_description_en TEXT,
        
        -- Estonian
        title_et VARCHAR(255),
        content_et TEXT,
        meta_title_et VARCHAR(255),
        meta_description_et TEXT,
        
        -- Russian
        title_ru VARCHAR(255),
        content_ru TEXT,
        meta_title_ru VARCHAR(255),
        meta_description_ru TEXT,
        
        views INTEGER DEFAULT 0,
        published_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY (author_id) REFERENCES users(id)
    )
");

// Create index for faster slug lookups
$conn->exec("CREATE INDEX IF NOT EXISTS idx_blog_posts_slug ON blog_posts(slug)");
$conn->exec("CREATE INDEX IF NOT EXISTS idx_blog_posts_status ON blog_posts(status)");

echo "Blog posts table created successfully.\n";
