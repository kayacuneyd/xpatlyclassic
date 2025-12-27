<?php

return [
    'connection' => $_ENV['DB_CONNECTION'] ?? 'sqlite',

    'sqlite' => [
        'path' => $_ENV['DB_PATH'] ?? 'storage/database/database.sqlite'
    ],

    'mysql' => [
        'host' => $_ENV['DB_HOST'] ?? 'localhost',
        'port' => $_ENV['DB_PORT'] ?? 3306,
        'database' => $_ENV['DB_DATABASE'] ?? 'xpatly',
        'username' => $_ENV['DB_USERNAME'] ?? 'root',
        'password' => $_ENV['DB_PASSWORD'] ?? ''
    ]
];
