<?php

require __DIR__ . '/../vendor/autoload.php';

use Models\Listing;
use Models\ListingImage;
use Core\Database;

echo "=== Seeding Property Images ===\n";

// 1. Get all listings
echo "Fetching listings...\n";
$db = new Database();
// We want all listings, regardless of status, or maybe just active/pending?
// Let's get all to be safe and thorough.
$stmt = $db->query("SELECT id FROM listings");
$listings = $stmt->fetchAll();

if (empty($listings)) {
    die("No listings found in database.\n");
}

echo "Found " . count($listings) . " listings.\n";

// 2. Get available images
$assetsDir = __DIR__ . '/../public/assets/images';
$sourceImages = glob($assetsDir . '/property_*.png');

if (empty($sourceImages)) {
    die("No source images found in $assetsDir\n");
}
echo "Found " . count($sourceImages) . " source images.\n";

// 3. Seed images
$totalAssigned = 0;

foreach ($listings as $listing) {
    $listingId = $listing['id'];
    $targetDir = __DIR__ . '/../public/uploads/listings/' . $listingId;

    if (!is_dir($targetDir)) {
        if (!mkdir($targetDir, 0755, true)) {
            echo "Failed to create directory $targetDir\n";
            continue;
        }
    }

    // Check if listing already has images
    $existingCount = ListingImage::countByListing($listingId);
    echo "Listing #$listingId has $existingCount existing images.\n";

    // Pick 1-3 random images
    $count = rand(1, 3);
    $keys = array_rand($sourceImages, $count);
    if (!is_array($keys)) {
        $keys = [$keys];
    }

    echo "Listing #$listingId: Adding $count new images...\n";

    // Unset existing primary images
    $db->update('listing_images', ['is_primary' => 0], ['listing_id' => $listingId]);

    $order = $existingCount; // Start ordering after existing images
    foreach ($keys as $key) {
        $sourcePath = $sourceImages[$key];
        $ext = pathinfo($sourcePath, PATHINFO_EXTENSION);

        // Create unique filename
        $newFilename = uniqid('img_') . '.' . $ext;
        $targetPath = $targetDir . '/' . $newFilename;

        if (copy($sourcePath, $targetPath)) {
            // Generate thumbnail
            $thumbPath = $targetDir . '/' . str_replace('.' . $ext, '_thumb.jpg', $newFilename);
            createSeedThumbnail($targetPath, $thumbPath, 400, 300);

            // Insert into DB
            $db->insert('listing_images', [
                'listing_id' => $listingId,
                'filename' => $newFilename,
                'original_filename' => basename($sourcePath),
                'sort_order' => $order,
                'is_primary' => ($order === $existingCount ? 1 : 0), // Make the first NEW image primary
                'created_at' => date('Y-m-d H:i:s')
            ]);
            $order++;
            $totalAssigned++;
        } else {
            echo "  Error copying " . basename($sourcePath) . "\n";
        }
    }
}

function createSeedThumbnail($src, $dest, $width, $height)
{
    $info = getimagesize($src);
    $mime = $info['mime'];

    switch ($mime) {
        case 'image/jpeg':
            $image = imagecreatefromjpeg($src);
            break;
        case 'image/png':
            $image = imagecreatefrompng($src);
            break;
        case 'image/webp':
            $image = imagecreatefromwebp($src);
            break;
        default:
            return false;
    }

    if (!$image)
        return false;

    $origWidth = imagesx($image);
    $origHeight = imagesy($image);

    $thumb = imagecreatetruecolor($width, $height);

    // Preserve transparency for PNG/WebP
    if ($mime == 'image/png' || $mime == 'image/webp') {
        imagecolortransparent($thumb, imagecolorallocatealpha($thumb, 0, 0, 0, 127));
        imagealphablending($thumb, false);
        imagesavealpha($thumb, true);
    }

    imagecopyresampled($thumb, $image, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);

    // Save as JPG for consistency with view code
    imagejpeg($thumb, $dest, 80);

    imagedestroy($image);
    imagedestroy($thumb);
    return true;
}

echo "\nDone! Assigned $totalAssigned images total.\n";
