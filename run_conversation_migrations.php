<?php

require __DIR__ . '/vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

use Core\Database;

echo "Running Conversation Migrations...\n\n";

$db = new Database();
$conn = $db->getPDO();

$migrations = [
    'migrations/018_create_conversations_table.php',
    'migrations/019_update_messages_for_conversations.php',
    'migrations/020_migrate_existing_messages_to_conversations.php'
];

foreach ($migrations as $file) {
    echo "Running: " . basename($file) . "... ";

    try {
        $migration = require __DIR__ . '/' . $file;

        if (isset($migration['up']) && is_callable($migration['up'])) {
            $migration['up']($conn);
            echo "✓ Success\n";
        } else {
            echo "✗ Skipped (invalid format)\n";
        }
    } catch (Exception $e) {
        echo "✗ Failed: " . $e->getMessage() . "\n";
    }
}

echo "\n✅ All conversation migrations completed!\n";
