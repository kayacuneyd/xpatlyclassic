<?php

/**
 * One-time script to convert existing JPEG/PNG images to WebP
 * Run this script once to convert all existing listing images
 *
 * Usage: php convert-existing-to-webp.php
 */

echo "=== WebP Conversion Script ===\n";
echo "Converting existing images to WebP format...\n\n";

$uploadDir = __DIR__ . '/public/uploads/listings';
$converted = 0;
$skipped = 0;
$errors = 0;

// Check if WebP is supported
if (!function_exists('imagewebp')) {
    die("ERROR: imagewebp() function not available. WebP support not enabled in PHP.\n");
}

// Scan all listing directories
$listingDirs = glob($uploadDir . '/*/');

if (empty($listingDirs)) {
    die("No listing directories found in $uploadDir\n");
}

echo "Found " . count($listingDirs) . " listing directories\n\n";

foreach ($listingDirs as $listingDir) {
    $listingId = basename($listingDir);
    echo "Processing listing #$listingId:\n";

    // Find all JPG and PNG files
    $images = array_merge(
        glob($listingDir . '*.jpg'),
        glob($listingDir . '*.jpeg'),
        glob($listingDir . '*.JPG'),
        glob($listingDir . '*.JPEG'),
        glob($listingDir . '*.png'),
        glob($listingDir . '*.PNG')
    );

    if (empty($images)) {
        echo "  No images found\n\n";
        continue;
    }

    foreach ($images as $imagePath) {
        $filename = basename($imagePath);
        $webpPath = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $imagePath);

        // Skip if WebP already exists
        if (file_exists($webpPath)) {
            echo "  ✓ $filename (WebP exists, skipping)\n";
            $skipped++;
            continue;
        }

        // Load the image
        $image = null;
        $ext = strtolower(pathinfo($imagePath, PATHINFO_EXTENSION));

        try {
            if ($ext === 'jpg' || $ext === 'jpeg') {
                $image = @imagecreatefromjpeg($imagePath);
            } elseif ($ext === 'png') {
                $image = @imagecreatefrompng($imagePath);
            }

            if ($image === false) {
                echo "  ✗ $filename (Failed to load)\n";
                $errors++;
                continue;
            }

            // Convert to WebP
            if (imagewebp($image, $webpPath, 85)) {
                $originalSize = filesize($imagePath);
                $webpSize = filesize($webpPath);
                $savings = $originalSize - $webpSize;
                $savingsPercent = round(($savings / $originalSize) * 100, 1);

                echo "  ✓ $filename → " . basename($webpPath) .
                     " (saved " . number_format($savings) . " bytes, {$savingsPercent}%)\n";
                $converted++;
            } else {
                echo "  ✗ $filename (WebP conversion failed)\n";
                $errors++;
            }

            imagedestroy($image);

        } catch (Exception $e) {
            echo "  ✗ $filename (Error: " . $e->getMessage() . ")\n";
            $errors++;
        }
    }

    echo "\n";
}

// Summary
echo "=== Conversion Complete ===\n";
echo "Converted: $converted images\n";
echo "Skipped:   $skipped images (already had WebP)\n";
echo "Errors:    $errors images\n";

if ($converted > 0) {
    echo "\n✓ Success! New uploads will automatically generate WebP versions.\n";
    echo "✓ Your .htaccess is already configured to serve WebP to supporting browsers.\n";
} else {
    echo "\n⚠ No images were converted. Check if images exist or if they already have WebP versions.\n";
}
