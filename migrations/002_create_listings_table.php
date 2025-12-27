<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS listings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL,
        title VARCHAR(200) NOT NULL,
        description TEXT,
        category VARCHAR(20) NOT NULL,
        deal_type VARCHAR(10) NOT NULL,
        region VARCHAR(50),
        settlement VARCHAR(100),
        address TEXT,
        latitude DECIMAL(10, 8),
        longitude DECIMAL(11, 8),
        rooms INTEGER,
        area_sqm DECIMAL(8, 2),
        price DECIMAL(10, 2),
        price_per_sqm DECIMAL(8, 2),
        condition VARCHAR(30),
        extras TEXT,
        youtube_url VARCHAR(255),
        status VARCHAR(20) DEFAULT 'pending',
        is_available BOOLEAN DEFAULT 1,
        expat_friendly BOOLEAN DEFAULT 0,
        pets_allowed BOOLEAN DEFAULT 0,
        views INTEGER DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )",

    'down' => "DROP TABLE IF EXISTS listings"
];
