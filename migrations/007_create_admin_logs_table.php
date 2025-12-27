<?php

return [
    'up' => "CREATE TABLE IF NOT EXISTS admin_logs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        admin_id INTEGER NOT NULL,
        action VARCHAR(50) NOT NULL,
        target_type VARCHAR(50),
        target_id INTEGER,
        changes TEXT,
        reason TEXT,
        ip_address VARCHAR(45),
        user_agent TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (admin_id) REFERENCES users(id)
    )",

    'down' => "DROP TABLE IF EXISTS admin_logs"
];
