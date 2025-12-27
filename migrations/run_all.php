<?php

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

use Core\Database;

echo "Running database migrations...\n\n";

$db = new Database();

// Get all migration files
$migrationFiles = glob(__DIR__ . '/*.php');
sort($migrationFiles);

foreach ($migrationFiles as $file) {
    if (basename($file) === 'run_all.php' || basename($file) === 'mysql_migration.php') {
        continue;
    }

    echo "Running: " . basename($file) . "... ";

    $migration = require $file;

    try {
        $db->query($migration['up']);
        echo "✓ Success\n";
    } catch (Exception $e) {
        echo "✗ Failed: " . $e->getMessage() . "\n";
    }
}

// Create indexes for better performance
echo "\nCreating indexes...\n";

$indexes = [
    "CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)",
    "CREATE INDEX IF NOT EXISTS idx_users_google_id ON users(google_id)",
    "CREATE INDEX IF NOT EXISTS idx_listings_user_id ON listings(user_id)",
    "CREATE INDEX IF NOT EXISTS idx_listings_status ON listings(status)",
    "CREATE INDEX IF NOT EXISTS idx_listings_region ON listings(region)",
    "CREATE INDEX IF NOT EXISTS idx_listings_category ON listings(category)",
    "CREATE INDEX IF NOT EXISTS idx_listings_expat_friendly ON listings(expat_friendly)",
    "CREATE INDEX IF NOT EXISTS idx_listing_images_listing_id ON listing_images(listing_id)",
    "CREATE INDEX IF NOT EXISTS idx_messages_listing_id ON messages(listing_id)",
    "CREATE INDEX IF NOT EXISTS idx_favorites_user_id ON favorites(user_id)",
    "CREATE INDEX IF NOT EXISTS idx_favorites_listing_id ON favorites(listing_id)",
];

foreach ($indexes as $index) {
    try {
        $db->query($index);
        echo "✓ Index created\n";
    } catch (Exception $e) {
        echo "✗ Index failed: " . $e->getMessage() . "\n";
    }
}

// Create default admin user
echo "\nCreating default admin user...\n";

try {
    $existingAdmin = $db->selectOne('users', ['email' => 'admin@xpatly.com']);

    if (!$existingAdmin) {
        $db->insert('users', [
            'full_name' => 'Super Admin',
            'email' => 'admin@xpatly.com',
            'password_hash' => password_hash('Admin123456!', PASSWORD_BCRYPT, ['cost' => 12]),
            'role' => 'super_admin',
            'is_verified' => 1,
            'email_verified' => 1,
            'phone_verified' => 1,
            'locale' => 'en',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
        echo "✓ Admin user created (Email: admin@xpatly.com, Password: Admin123456!)\n";
    } else {
        echo "✓ Admin user already exists\n";
    }
} catch (Exception $e) {
    echo "✗ Failed to create admin: " . $e->getMessage() . "\n";
}

echo "\n✓ All migrations completed!\n";
