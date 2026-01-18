<?php
/**
 * Emergency Password Hash Fix Script
 * Run this ONCE to fix corrupted password hash
 *
 * Usage: php fix_password_hash.php
 * Or visit: https://xpatly.eu/fix_password_hash.php
 */

// Load Composer autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load helper functions
require_once __DIR__ . '/core/helpers.php';

use Models\User;

// Security: Only allow access from localhost or specific IP
$allowedIPs = ['127.0.0.1', '::1']; // Add your IP here if needed
if (!in_array($_SERVER['REMOTE_ADDR'] ?? '', $allowedIPs) && php_sapi_name() !== 'cli') {
    die('Access denied. Run this script from command line or localhost only.');
}

echo "=== Password Hash Fix Script ===\n\n";

// Email to fix
$email = 'kayacuneyd@gmail.com';

// New password
$newPassword = 'SecurePassword2026!';

// Find user
$user = User::findByEmail($email);
if (!$user) {
    die("ERROR: User not found: $email\n");
}

echo "Found user:\n";
echo "  ID: {$user['id']}\n";
echo "  Email: {$user['email']}\n";
echo "  Current hash length: " . strlen($user['password_hash']) . "\n";
echo "  Current hash prefix: " . substr($user['password_hash'], 0, 7) . "\n\n";

// Check if hash is already bcrypt
$isBcrypt = (
    strlen($user['password_hash']) === 60 &&
    (str_starts_with($user['password_hash'], '$2y$') ||
     str_starts_with($user['password_hash'], '$2a$') ||
     str_starts_with($user['password_hash'], '$2b$'))
);

if ($isBcrypt) {
    echo "✓ Password hash is already valid bcrypt format!\n";
    echo "No fix needed.\n";
    exit(0);
}

echo "⚠ Password hash is CORRUPTED (not bcrypt)\n";
echo "Generating new bcrypt hash...\n\n";

// Generate new bcrypt hash
$newHash = password_hash($newPassword, PASSWORD_BCRYPT, ['cost' => 12]);

echo "New hash generated:\n";
echo "  Length: " . strlen($newHash) . "\n";
echo "  Prefix: " . substr($newHash, 0, 7) . "\n";
echo "  Full hash: $newHash\n\n";

// Update database
echo "Updating database...\n";
$updated = User::update($user['id'], [
    'password_hash' => $newHash
]);

if ($updated > 0) {
    echo "✓ SUCCESS! Password hash updated.\n\n";

    // Verify
    $verifyUser = User::find($user['id']);
    echo "Verification:\n";
    echo "  Hash length: " . strlen($verifyUser['password_hash']) . "\n";
    echo "  Hash prefix: " . substr($verifyUser['password_hash'], 0, 4) . "\n";

    $isBcryptNow = str_starts_with($verifyUser['password_hash'], '$2y$') ||
                   str_starts_with($verifyUser['password_hash'], '$2a$') ||
                   str_starts_with($verifyUser['password_hash'], '$2b$');

    if ($isBcryptNow && strlen($verifyUser['password_hash']) === 60) {
        echo "  Status: ✓ Valid bcrypt hash\n\n";
        echo "You can now login with:\n";
        echo "  Email: $email\n";
        echo "  Password: $newPassword\n\n";
        echo "⚠ IMPORTANT: Delete this script after use!\n";
        echo "  rm fix_password_hash.php\n";
    } else {
        echo "  Status: ✗ Still not valid!\n";
    }
} else {
    echo "✗ ERROR: Failed to update database!\n";
    echo "Rows affected: $updated\n";
}

echo "\n=== End of Script ===\n";
