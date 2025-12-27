<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS saved_searches (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_email VARCHAR(100) NOT NULL,
        search_params TEXT NOT NULL,
        alert_frequency VARCHAR(20) DEFAULT 'daily',
        is_active BOOLEAN DEFAULT 1,
        last_sent DATETIME,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )",

    'down' => "DROP TABLE IF EXISTS saved_searches"
];
