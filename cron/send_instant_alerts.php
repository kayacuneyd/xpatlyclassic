<?php

/**
 * Instant Email Alerts Cron Job
 * Run every 5 minutes: */5 * * * * php /path/to/cron/send_instant_alerts.php
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
use Models\Listing;
use PHPMailer\PHPMailer\PHPMailer;

echo "[" . date('Y-m-d H:i:s') . "] Starting instant alerts check...\n";

// Get all active instant alerts
$instantAlerts = SavedSearch::getActive('instant');

echo "Found " . count($instantAlerts) . " instant alert subscriptions\n";

$sentCount = 0;

foreach ($instantAlerts as $alert) {
    // Get search parameters
    $searchParams = json_decode($alert['search_params'], true);

    // Check for new listings in last 5 minutes
    $since = date('Y-m-d H:i:s', strtotime('-5 minutes'));

    $db = new Core\Database();
    $sql = "SELECT * FROM listings WHERE status = 'active' AND created_at > ? ORDER BY created_at DESC";
    $stmt = $db->query($sql, [$since]);
    $newListings = $stmt->fetchAll();

    if (empty($newListings)) {
        continue;
    }

    // Filter listings by search criteria
    $matchingListings = [];
    foreach ($newListings as $listing) {
        if (matchesCriteria($listing, $searchParams)) {
            $matchingListings[] = $listing;
        }
    }

    if (empty($matchingListings)) {
        continue;
    }

    // Send email
    $sent = sendInstantAlertEmail($alert['user_email'], $matchingListings, $searchParams);

    if ($sent) {
        SavedSearch::updateLastSent($alert['id']);
        $sentCount++;
        echo "✓ Sent instant alert to {$alert['user_email']} (" . count($matchingListings) . " new listings)\n";
    } else {
        echo "✗ Failed to send alert to {$alert['user_email']}\n";
    }
}

echo "Completed! Sent $sentCount instant alerts.\n";

// Helper functions
function matchesCriteria(array $listing, array $criteria): bool
{
    if (!empty($criteria['region']) && $listing['region'] !== $criteria['region']) {
        return false;
    }

    if (!empty($criteria['category']) && $listing['category'] !== $criteria['category']) {
        return false;
    }

    if (!empty($criteria['deal_type']) && $listing['deal_type'] !== $criteria['deal_type']) {
        return false;
    }

    if (!empty($criteria['price_min']) && $listing['price'] < $criteria['price_min']) {
        return false;
    }

    if (!empty($criteria['price_max']) && $listing['price'] > $criteria['price_max']) {
        return false;
    }

    if (!empty($criteria['rooms_min']) && $listing['rooms'] < $criteria['rooms_min']) {
        return false;
    }

    if (!empty($criteria['rooms_max']) && $listing['rooms'] > $criteria['rooms_max']) {
        return false;
    }

    if (!empty($criteria['expat_friendly']) && !$listing['expat_friendly']) {
        return false;
    }

    return true;
}

function sendInstantAlertEmail(string $email, array $listings, array $criteria): bool
{
    $mail = new PHPMailer(true);

    try {
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['MAIL_USERNAME'];
        $mail->Password = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = $_ENV['MAIL_PORT'];

        // Recipients
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($email);

        // Content
        $mail->isHTML(true);
        $mail->Subject = '🏠 New Listing Alert - ' . count($listings) . ' property matches your search!';

        // Build email body
        $body = '<h2>New Listings Just Posted!</h2>';
        $body .= '<p>We found ' . count($listings) . ' new listing(s) matching your saved search:</p>';

        foreach ($listings as $listing) {
            $body .= '<div style="border: 1px solid #ddd; padding: 15px; margin: 10px 0; border-radius: 5px;">';
            $body .= '<h3>' . htmlspecialchars($listing['title']) . '</h3>';
            $body .= '<p>📍 ' . htmlspecialchars($listing['settlement']) . ', ' . htmlspecialchars($listing['region']) . '</p>';
            $body .= '<p>💰 €' . number_format($listing['price'], 0) . '/month | 📏 ' . $listing['area_sqm'] . ' m² | 🚪 ' . $listing['rooms'] . ' rooms</p>';
            if ($listing['expat_friendly']) {
                $body .= '<p style="color: green;">✓ Expat-Friendly</p>';
            }
            $body .= '<a href="' . $_ENV['APP_URL'] . '/listings/' . $listing['id'] . '" style="display: inline-block; padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin-top: 10px;">View Listing</a>';
            $body .= '</div>';
        }

        $body .= '<p style="margin-top: 20px; color: #666; font-size: 12px;">You\'re receiving this because you set up instant alerts. <a href="' . $_ENV['APP_URL'] . '/saved-searches">Manage your alerts</a></p>';

        $mail->Body = $body;
        $mail->send();

        return true;
    } catch (Exception $e) {
        error_log("Instant alert email failed: {$mail->ErrorInfo}");
        return false;
    }
}
