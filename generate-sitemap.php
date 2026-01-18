<?php

// Generate dynamic sitemap.xml
// Run this script manually or via cron to update sitemap

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/core/helpers.php';

use Models\Listing;

// Get all active listings
$db = new \Core\Database();
$sql = "SELECT id, updated_at, created_at FROM listings WHERE status = 'active' AND is_available = 1 ORDER BY created_at DESC";
$result = $db->query($sql);
$listings = $result->fetchAll();

// Start XML
$xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
$xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"' . "\n";
$xml .= '        xmlns:xhtml="http://www.w3.org/1999/xhtml">' . "\n\n";

// Homepage
$xml .= "    <url>\n";
$xml .= "        <loc>https://xpatly.eu/</loc>\n";
$xml .= "        <changefreq>daily</changefreq>\n";
$xml .= "        <priority>1.0</priority>\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/\" />\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/\" />\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/\" />\n";
$xml .= "    </url>\n\n";

// Search/Listings
$xml .= "    <url>\n";
$xml .= "        <loc>https://xpatly.eu/en/listings</loc>\n";
$xml .= "        <changefreq>hourly</changefreq>\n";
$xml .= "        <priority>0.9</priority>\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/listings\" />\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/listings\" />\n";
$xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/listings\" />\n";
$xml .= "    </url>\n\n";

// Static pages
$pages = [
    ['path' => 'about', 'priority' => '0.7', 'changefreq' => 'monthly'],
    ['path' => 'contact', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['path' => 'faq', 'priority' => '0.6', 'changefreq' => 'monthly'],
    ['path' => 'blog', 'priority' => '0.7', 'changefreq' => 'weekly'],
    ['path' => 'privacy', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['path' => 'terms', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['path' => 'imprint', 'priority' => '0.3', 'changefreq' => 'yearly'],
    ['path' => 'login', 'priority' => '0.5', 'changefreq' => 'monthly'],
    ['path' => 'register', 'priority' => '0.5', 'changefreq' => 'monthly'],
    ['path' => 'listings/create', 'priority' => '0.8', 'changefreq' => 'monthly'],
];

foreach ($pages as $page) {
    $xml .= "    <url>\n";
    $xml .= "        <loc>https://xpatly.eu/en/{$page['path']}</loc>\n";
    $xml .= "        <changefreq>{$page['changefreq']}</changefreq>\n";
    $xml .= "        <priority>{$page['priority']}</priority>\n";
    if (!in_array($page['path'], ['login', 'register'])) {
        $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/{$page['path']}\" />\n";
        $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/{$page['path']}\" />\n";
        $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/{$page['path']}\" />\n";
    }
    $xml .= "    </url>\n\n";
}

// Active listings
foreach ($listings as $listing) {
    $lastmod = date('Y-m-d', strtotime($listing['updated_at'] ?? $listing['created_at']));
    $xml .= "    <url>\n";
    $xml .= "        <loc>https://xpatly.eu/en/listings/{$listing['id']}</loc>\n";
    $xml .= "        <lastmod>{$lastmod}</lastmod>\n";
    $xml .= "        <changefreq>weekly</changefreq>\n";
    $xml .= "        <priority>0.8</priority>\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/listings/{$listing['id']}\" />\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/listings/{$listing['id']}\" />\n";
    $xml .= "        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/listings/{$listing['id']}\" />\n";
    $xml .= "    </url>\n\n";
}

$xml .= "</urlset>\n";

// Write to file
file_put_contents(__DIR__ . '/public/sitemap.xml', $xml);

echo "✓ Sitemap generated successfully!\n";
echo "✓ Total listings: " . count($listings) . "\n";
echo "✓ File: public/sitemap.xml\n";
