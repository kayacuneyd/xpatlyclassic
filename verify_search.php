<?php

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/core/helpers.php';

use Models\Listing;
use Core\Translation;

// Mock environment and DB connection (assuming standard setup works)
// We need to load env vars manually if not running through public/index.php
if (file_exists(__DIR__ . '/.env')) {
    $lines = file(__DIR__ . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0)
            continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

Translation::setLocale('en');

function testFilter($name, $filters)
{
    echo "Testing filter: $name... ";
    try {
        $results = Listing::getActive($filters);
        $count = count($results);
        echo "OK (Found: $count)\n";
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}

// Test Cases
echo "Starting Search Verification...\n";

// 1. Basic Search (No filters)
testFilter('No Filters', []);

// 2. Price Range
testFilter('Price Range 0-5000', ['price_min' => 0, 'price_max' => 5000]);
testFilter('Price Min Only', ['price_min' => 100]);
testFilter('Price Max Only', ['price_max' => 500]);

// 3. Rooms
testFilter('Rooms 1-3', ['rooms_min' => 1, 'rooms_max' => 3]);
testFilter('Rooms Exact', ['rooms_min' => 2, 'rooms_max' => 2]);

// 4. Region & Category
testFilter('Region: Tallinn', ['region' => 'Tallinn']);
testFilter('Category: Apartment', ['category' => 'apartment']);
testFilter('Deal Type: Rent', ['deal_type' => 'rent']);

// 5. Combined Filters
testFilter('Complex: Tallinn + Rent + Price < 1000', [
    'region' => 'Tallinn',
    'deal_type' => 'rent',
    'price_max' => 1000
]);

// 6. Extras (empty strings, arrays)
testFilter('Extras: Balcony (array)', ['extras' => ['balcony']]);
testFilter('Extras: Balcony (string - simulation)', ['extras' => 'balcony']); // Should fail if code expects array but user passes string? Listing model expects array loop.

// 7. Edge Cases
testFilter('Invalid Numeric Strings', ['price_min' => 'abc', 'price_max' => '1000']); // wrapper should cast to float/int
testFilter('SQL Injection Attempt', ['q' => "' OR 1=1 --"]);

echo "Verification Complete.\n";
