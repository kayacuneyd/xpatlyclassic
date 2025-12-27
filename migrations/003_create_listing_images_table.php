<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS listing_images (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL,
        filename VARCHAR(255) NOT NULL,
        original_filename VARCHAR(255),
        sort_order INTEGER DEFAULT 0,
        is_primary BOOLEAN DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
    )",

    'down' => "DROP TABLE IF EXISTS listing_images"
];
