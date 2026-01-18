<?php

/**
 * Test Login Flow Locally
 * This simulates what happens when user submits login form
 */

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Load helper functions
require __DIR__ . '/core/helpers.php';

use Core\Auth;
use Core\Session;
use Models\User;

echo "=== Login Flow Test ===\n\n";

// Start session (like production would)
Session::start();

echo "Step 1: Check current user state\n";
echo "  Authenticated: " . (Auth::check() ? 'YES' : 'NO') . "\n";
echo "  Session ID: " . session_id() . "\n\n";

// Test credentials
$email = 'kayacuneyd@gmail.com';
$password = 'SecurePassword2026!';

echo "Step 2: Attempt login\n";
echo "  Email: $email\n";
echo "  Password: $password\n\n";

// Simulate login attempt (what AuthController does)
$success = Auth::attempt($email, $password, false);

echo "Step 3: Login result\n";
echo "  Success: " . ($success ? 'YES' : 'NO') . "\n\n";

if ($success) {
    echo "Step 4: Check session after login\n";
    echo "  Authenticated: " . (Auth::check() ? 'YES' : 'NO') . "\n";
    echo "  User ID: " . (Auth::id() ?: 'NULL') . "\n";
    echo "  User: " . print_r(Auth::user(), true) . "\n";
    echo "  Session ID: " . session_id() . "\n";
    echo "  Session data: " . print_r($_SESSION, true) . "\n";
} else {
    echo "❌ LOGIN FAILED\n\n";
    echo "Debugging info:\n";

    // Check user exists
    $user = User::findByEmail($email);
    if (!$user) {
        echo "  ERROR: User not found with email: $email\n";
    } else {
        echo "  ✓ User found: ID {$user['id']}\n";
        echo "  Hash length: " . strlen($user['password_hash']) . "\n";
        echo "  Hash prefix: " . substr($user['password_hash'], 0, 10) . "\n";

        // Test password verification
        $verified = password_verify($password, $user['password_hash']);
        echo "  Password verify: " . ($verified ? 'YES' : 'NO') . "\n";

        if (!$verified) {
            echo "\n  Trying different password...\n";
            $testPasswords = [
                'password',
                'Password123',
                'xpatly2024',
                'admin123'
            ];
            foreach ($testPasswords as $testPw) {
                if (password_verify($testPw, $user['password_hash'])) {
                    echo "  ✓ Password is: $testPw\n";
                    break;
                }
            }
        }
    }
}

echo "\n=== End of Test ===\n";
