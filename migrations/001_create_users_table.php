<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        full_name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        phone VARCHAR(20),
        password_hash VARCHAR(255) NOT NULL,
        role VARCHAR(20) DEFAULT 'user',
        is_verified BOOLEAN DEFAULT 0,
        phone_verified BOOLEAN DEFAULT 0,
        email_verified BOOLEAN DEFAULT 0,
        verification_token VARCHAR(64),
        google_id VARCHAR(100),
        locale VARCHAR(5) DEFAULT 'en',
        reset_token VARCHAR(64),
        reset_expires DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",

    'down' => "DROP TABLE IF EXISTS users"
];
