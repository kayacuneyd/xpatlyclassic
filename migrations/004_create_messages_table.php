<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS messages (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        listing_id INTEGER NOT NULL,
        sender_email VARCHAR(100) NOT NULL,
        sender_name VARCHAR(100),
        message TEXT NOT NULL,
        read_at DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (listing_id) REFERENCES listings(id) ON DELETE CASCADE
    )",

    'down' => "DROP TABLE IF EXISTS messages"
];
