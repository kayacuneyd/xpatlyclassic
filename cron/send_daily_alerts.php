<?php

/**
 * Daily Email Alerts Cron Job
 * Run daily at 9:00 AM: 0 9 * * * php /path/to/cron/send_daily_alerts.php
 */

require __DIR__ . '/../vendor/autoload.php';

// Load environment
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

use Models\SavedSearch;
use PHPMailer\PHPMailer\PHPMailer;

echo "[" . date('Y-m-d H:i:s') . "] Starting daily alerts...\n";

$dailyAlerts = SavedSearch::getActive('daily');
echo "Found " . count($dailyAlerts) . " daily alert subscriptions\n";

$sentCount = 0;

foreach ($dailyAlerts as $alert) {
    $searchParams = json_decode($alert['search_params'], true);
    $since = date('Y-m-d H:i:s', strtotime('-24 hours'));

    $db = new Core\Database();
    $sql = "SELECT * FROM listings WHERE status = 'active' AND created_at > ? ORDER BY created_at DESC";
    $stmt = $db->query($sql, [$since]);
    $newListings = $stmt->fetchAll();

    if (empty($newListings)) {
        continue;
    }

    // Filter by criteria
    $matchingListings = array_filter($newListings, function($listing) use ($searchParams) {
        return matchesCriteria($listing, $searchParams);
    });

    if (empty($matchingListings)) {
        continue;
    }

    if (sendDailyDigest($alert['user_email'], $matchingListings)) {
        SavedSearch::updateLastSent($alert['id']);
        $sentCount++;
        echo "✓ Sent daily digest to {$alert['user_email']}\n";
    }
}

echo "Completed! Sent $sentCount daily digests.\n";

function matchesCriteria($listing, $criteria) {
    if (!empty($criteria['region']) && $listing['region'] !== $criteria['region']) return false;
    if (!empty($criteria['category']) && $listing['category'] !== $criteria['category']) return false;
    if (!empty($criteria['price_min']) && $listing['price'] < $criteria['price_min']) return false;
    if (!empty($criteria['price_max']) && $listing['price'] > $criteria['price_max']) return false;
    if (!empty($criteria['expat_friendly']) && !$listing['expat_friendly']) return false;
    return true;
}

function sendDailyDigest($email, $listings) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = '📬 Daily Property Digest - ' . count($listings) . ' new listings in the last 24h';

        $body = '<h2>Your Daily Property Update</h2>';
        $body .= '<p>Here are the latest properties matching your search (last 24 hours):</p>';

        foreach ($listings as $listing) {
            $body .= '<div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0;">';
            $body .= '<h3>' . htmlspecialchars($listing['title']) . '</h3>';
            $body .= '<p>💰 €' . number_format($listing['price'], 0) . '/mo | 📏 ' . $listing['area_sqm'] . ' m²</p>';
            $body .= '<a href="' . $_ENV['APP_URL'] . '/listings/' . $listing['id'] . '">View Listing</a>';
            $body .= '</div>';
        }

        $mail->Body = $body;
        $mail->send();
        return true;
    } catch (Exception $e) {
        return false;
    }
}
