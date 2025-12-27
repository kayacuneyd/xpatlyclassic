<?php

return [
    'name' => $_ENV['APP_NAME'] ?? 'Xpatly',
    'env' => $_ENV['APP_ENV'] ?? 'production',
    'debug' => filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN),
    'url' => $_ENV['APP_URL'] ?? 'https://xpatly.com',

    'locale' => 'en',
    'fallback_locale' => 'en',
    'supported_locales' => ['en', 'et', 'ru'],

    'timezone' => 'Europe/Tallinn',

    'session' => [
        'lifetime' => (int)($_ENV['SESSION_LIFETIME'] ?? 1440), // 24 hours in minutes
        'secure' => filter_var($_ENV['SESSION_SECURE'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'csrf_token_name' => $_ENV['CSRF_TOKEN_NAME'] ?? '_token'
    ],

    'cache' => [
        'enabled' => filter_var($_ENV['CACHE_ENABLED'] ?? true, FILTER_VALIDATE_BOOLEAN),
        'lifetime' => (int)($_ENV['CACHE_LIFETIME'] ?? 900) // 15 minutes
    ],

    'upload' => [
        'max_file_size' => (int)($_ENV['MAX_FILE_SIZE'] ?? 5242880), // 5MB
        'max_files_per_listing' => (int)($_ENV['MAX_FILES_PER_LISTING'] ?? 40),
        'path' => $_ENV['UPLOAD_PATH'] ?? 'public/uploads/listings'
    ]
];
