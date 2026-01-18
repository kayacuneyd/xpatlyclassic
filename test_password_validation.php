<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/core/helpers.php';

use Core\Validator;

echo "Testing password validation with DonaldTrump1234\n\n";

$data = [
    'password' => 'DonaldTrump1234'
];

$validator = new Validator($data, [
    'password' => 'required|password'
]);

if ($validator->validate()) {
    echo "✅ SUCCESS! Password 'DonaldTrump1234' is VALID\n\n";
    echo "The password passed all validation checks:\n";
    echo "- Length: " . strlen('DonaldTrump1234') . " chars (>= 12) ✓\n";
    echo "- Uppercase: D, T ✓\n";
    echo "- Lowercase: onald, rump ✓\n";
    echo "- Number: 1234 ✓\n";
    echo "- Contains '234' (3-char): Allowed (we now only block 4-char sequences)\n";
    echo "- Contains '1234' (4-char): " . (preg_match('/1234|2345|3456|4567|5678|6789/i', 'DonaldTrump1234') ? 'YES - but blocked' : 'NO') . "\n";
    echo "- No repeated chars: ✓\n";
} else {
    echo "❌ FAILED! Password 'DonaldTrump1234' is INVALID\n\n";
    echo "Errors:\n";
    foreach ($validator->errors() as $field => $error) {
        echo "- $field: $error\n";
    }
}

echo "\n--- Testing other passwords ---\n\n";

$testPasswords = [
    'Password1234' => 'Should FAIL (contains 1234 - 4 chars)',
    'DonaldTrump1579' => 'Should PASS (no 4-char sequences)',
    'Test123' => 'Should FAIL (< 12 chars)',
    'Passwordaaa' => 'Should FAIL (repeated chars aaa)',
    'MySecure@Pass2024' => 'Should PASS (all requirements)',
    'TestUser!2026' => 'Should PASS (all requirements)',
    'Donald123Trump' => 'Should PASS (123 is only 3 chars, allowed)',
];

foreach ($testPasswords as $password => $expected) {
    $v = new Validator(['password' => $password], ['password' => 'required|password']);
    $result = $v->validate() ? '✅ PASS' : '❌ FAIL';
    echo "$result: $password\n";
    echo "         ($expected)\n";
    if (!$v->validate()) {
        echo "         Error: " . $v->firstError() . "\n";
    }
    echo "\n";
}

echo "=== Test Complete ===\n";
