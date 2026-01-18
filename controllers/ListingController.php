<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Core\Uploader;
use Models\Listing;
use Models\ListingImage;
use Models\Message;
use Models\User;

class ListingController
{
    public function show(string $id): void
    {
        $id = (int) $id;
        $listing = Listing::getWithOwner($id);

        if (!$listing) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
            exit;
        }

        if ($listing['status'] !== 'active' && !Auth::isAdmin()) {
            Flash::error(__('listing.not_approved') ?? 'This listing is not approved yet and cannot be viewed.');
            $redirect = $_SERVER['HTTP_REFERER'] ?? '/my-listings';
            header('Location: ' . $redirect);
            exit;
        }

        $listing = Listing::applyLocale($listing);

        // Increment views
        Listing::incrementViews($id);

        // Get images
        $images = ListingImage::getByListing($id);

        // Get extras as array
        $extras = json_decode($listing['extras'] ?? '{}', true);

        // Check if favorited by current user
        $isFavorited = false;
        if (Auth::check()) {
            $isFavorited = \Models\Favorite::isFavorited(Auth::id(), $id);
        }

        $data = [
            'title' => $listing['title'],
            'listing' => $listing,
            'images' => $images,
            'extras' => $extras,
            'isFavorited' => $isFavorited
        ];

        $this->view('listings/show', $data);
    }

    public function create(): void
    {
        Auth::requireAuth();

        $role = Auth::user()['role'] ?? 'user';
        if (!in_array($role, ['owner', 'super_admin'], true)) {
            Flash::warning(__('listing.role_required') ?? 'To create listings, switch your account to "I want to list a property" in your dashboard.');
            header('Location: /dashboard');
            exit;
        }

        // Check if user is verified (email only)
        if (!User::isEmailVerified(Auth::id()) && !Auth::isSuperAdmin()) {
            Flash::error(__('listing.verification_required'));
            header('Location: /dashboard');
            exit;
        }

        $this->view('listings/create', ['title' => __('listing.create')]);
    }

    public function store(): void
    {
        Auth::requireAuth();

        $role = Auth::user()['role'] ?? 'user';
        if (!in_array($role, ['owner', 'super_admin'], true)) {
            Flash::warning(__('listing.role_required') ?? 'To create listings, switch your account to "I want to list a property" in your dashboard.');
            header('Location: /dashboard');
            exit;
        }

        $isDraft = isset($_POST['save_draft']) && $_POST['save_draft'] === '1';

        if ($isDraft) {
            $validator = new Validator($_POST, [
                'title_en' => 'required|min:10|max:200|no_discrimination',
                'category' => 'required|in:apartment,house,room',
                'deal_type' => 'required|in:rent,sell',
            ]);
        } else {
            $validator = new Validator($_POST, [
                'title_en' => 'required|min:10|max:200|no_discrimination',
                'description_en' => 'required|min:50|no_discrimination',
                'title_et' => 'max:200|no_discrimination',
                'description_et' => 'no_discrimination',
                'title_ru' => 'max:200|no_discrimination',
                'description_ru' => 'no_discrimination',
                'category' => 'required|in:apartment,house,room',
                'deal_type' => 'required|in:rent,sell',
                'region' => 'required',
                'address' => 'required',
                'rooms' => 'required|integer|min_value:1',
                'area_sqm' => 'required|numeric|min_value:1|max_value:500',
                'price' => 'required|numeric|min_value:1',
                'condition' => 'required|in:new_development,good,renovated,needs_renovation'
            ]);
        }

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /listings/create');
            exit;
        }

        // Base fields derive from English as primary
        $_POST['title'] = $_POST['title_en'];
        $_POST['description'] = $_POST['description_en'] ?? '';

        $duplicate = Listing::checkDuplicate($_POST['address'], Auth::id());
        if ($duplicate) {
            Flash::warning(__('listing.duplicate_warning'));
        }

        // Calculate price per sqm when possible
        $pricePerSqm = null;
        $price = isset($_POST['price']) ? (float) $_POST['price'] : null;
        $area = isset($_POST['area_sqm']) ? (float) $_POST['area_sqm'] : null;
        if ($price && $area) {
            $pricePerSqm = $price / $area;
        }

        // Prepare extras
        $extras = [
            'balcony' => isset($_POST['balcony']) ? 1 : 0,
            'garage' => isset($_POST['garage']) ? 1 : 0,
            'sauna' => isset($_POST['sauna']) ? 1 : 0,
            'elevator' => isset($_POST['elevator']) ? 1 : 0,
            'fireplace' => isset($_POST['fireplace']) ? 1 : 0,
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'storage_room' => isset($_POST['storage_room']) ? 1 : 0,
        ];

        $settlement = trim((string) ($_POST['settlement'] ?? ''));
        if ($settlement === 'other') {
            $settlement = trim((string) ($_POST['settlement_other'] ?? ''));
        }
        if ($settlement === '') {
            $settlement = null;
        }

        // Create listing
        $listingId = Listing::create([
            'user_id' => Auth::id(),
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'],
            'deal_type' => $_POST['deal_type'],
            'region' => $_POST['region'] ?? null,
            'settlement' => $settlement,
            'address' => $_POST['address'] ?? null,
            'latitude' => $_POST['latitude'] ?? null,
            'longitude' => $_POST['longitude'] ?? null,
            'rooms' => isset($_POST['rooms']) ? (int) $_POST['rooms'] : null,
            'area_sqm' => isset($_POST['area_sqm']) ? (float) $_POST['area_sqm'] : null,
            'price' => isset($_POST['price']) ? (float) $_POST['price'] : null,
            'price_per_sqm' => $pricePerSqm,
            'condition' => $_POST['condition'] ?? null,
            'extras' => json_encode($extras),
            'youtube_url' => $_POST['youtube_url'] ?? null,
            'expat_friendly' => isset($_POST['expat_friendly']) ? 1 : 0,
            'pets_allowed' => isset($_POST['pets_allowed']) ? 1 : 0,
            'title_en' => $_POST['title_en'] ?? null,
            'title_et' => $_POST['title_et'] ?? null,
            'title_ru' => $_POST['title_ru'] ?? null,
            'description_en' => $_POST['description_en'] ?? null,
            'description_et' => $_POST['description_et'] ?? null,
            'description_ru' => $_POST['description_ru'] ?? null,
            'status' => $isDraft ? 'draft' : 'pending',
            'is_available' => $isDraft ? 0 : 1
        ]);

        // Handle image uploads with hybrid storage (R2 or local)
        if (!empty($_FILES['images']['name'][0])) {
            $uploader = new Uploader();
            $primaryIndex = isset($_POST['primary_image_index']) ? (int) $_POST['primary_image_index'] : 0;

            $files = $_FILES['images'];
            $normalized = [];
            if (isset($files['name']) && is_array($files['name'])) {
                foreach ($files['name'] as $key => $name) {
                    $normalized[] = [
                        'name' => $files['name'][$key],
                        'type' => $files['type'][$key],
                        'tmp_name' => $files['tmp_name'][$key],
                        'error' => $files['error'][$key],
                        'size' => $files['size'][$key]
                    ];
                }
            } else {
                $normalized[] = $files;
            }

            $uploadedImages = [];
            $count = 0;
            foreach ($normalized as $index => $file) {
                if ($count >= 10) {
                    break;
                }
                if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                    continue;
                }
                $options = $index === $primaryIndex ? $uploader->getPrimaryImageOptions() : [];
                $result = $uploader->uploadWithStorage($file, "listings/{$listingId}", 'listing_', $options);
                if ($result) {
                    $uploadedImages[] = $result;
                    $count++;
                }
            }

            if (!empty($uploadedImages)) {
                $primaryIndex = max(0, min($primaryIndex, count($uploadedImages) - 1));
            } else {
                $primaryIndex = 0;
            }

            foreach ($uploadedImages as $index => $image) {
                ListingImage::create([
                    'listing_id' => $listingId,
                    'filename' => $image['filename'],
                    'original_filename' => $image['original_name'],
                    'sort_order' => $index,
                    'is_primary' => $index === $primaryIndex ? 1 : 0
                ]);
            }
        }

        if ($isDraft) {
            Flash::success(__('listing.draft_saved') ?? 'Draft saved. You can finish it later.');
        } else {
            Flash::success(__('listing.created_success'));
        }
        header('Location: /my-listings');
        exit;
    }

    public function myListings(): void
    {
        Auth::requireAuth();

        $role = Auth::user()['role'] ?? 'user';
        if (!in_array($role, ['owner', 'super_admin'], true)) {
            Flash::warning(__('listing.role_required') ?? 'To manage listings, switch your account to "I want to list a property" in your dashboard.');
            header('Location: /dashboard');
            exit;
        }

        $status = $_GET['status'] ?? '';
        $allowedStatuses = ['active', 'pending', 'paused', 'archived', 'draft'];

        $allListings = Listing::getByUser(Auth::id());

        $counts = [
            'all' => count($allListings),
            'active' => 0,
            'pending' => 0,
            'paused' => 0,
            'archived' => 0,
            'draft' => 0,
        ];

        foreach ($allListings as $listing) {
            $statusKey = $listing['status'] ?? '';
            if (isset($counts[$statusKey])) {
                $counts[$statusKey]++;
            }
        }

        $listings = $allListings;
        if ($status && in_array($status, $allowedStatuses, true)) {
            $listings = array_values(array_filter($allListings, function ($listing) use ($status) {
                return ($listing['status'] ?? '') === $status;
            }));
        }

        $this->view('listings/my-listings', [
            'title' => __('listing.my_listings'),
            'listings' => $listings,
            'counts' => $counts
        ]);
    }

    public function edit(string $id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing) {
            Flash::error(__('listing.not_found'));
            header('Location: /my-listings');
            exit;
        }

        // Check ownership or admin
        if ($listing['user_id'] != Auth::id() && !Auth::isAdmin()) {
            Flash::error(__('errors.access_denied'));
            header('Location: /my-listings');
            exit;
        }

        $images = ListingImage::getByListing($id);
        $extras = json_decode($listing['extras'] ?? '{}', true);

        $this->view('listings/edit', [
            'title' => __('listing.edit'),
            'listing' => $listing,
            'images' => $images,
            'extras' => $extras
        ]);
    }

    public function update(string $id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing || ($listing['user_id'] != Auth::id() && !Auth::isAdmin())) {
            Flash::error(__('errors.access_denied'));
            header('Location: /my-listings');
            exit;
        }

        $validator = new Validator($_POST, [
            'title_en' => 'required|min:10|max:200|no_discrimination',
            'description_en' => 'required|min:50|no_discrimination',
            'title_et' => 'max:200|no_discrimination',
            'description_et' => 'no_discrimination',
            'title_ru' => 'max:200|no_discrimination',
            'description_ru' => 'no_discrimination',
            'price' => 'required|numeric|min_value:1',
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /listings/{$id}/edit");
            exit;
        }

        $_POST['title'] = $_POST['title_en'];
        $_POST['description'] = $_POST['description_en'];

        $pricePerSqm = $_POST['price'] / $listing['area_sqm'];

        $extras = [
            'balcony' => isset($_POST['balcony']) ? 1 : 0,
            'garage' => isset($_POST['garage']) ? 1 : 0,
            'sauna' => isset($_POST['sauna']) ? 1 : 0,
            'elevator' => isset($_POST['elevator']) ? 1 : 0,
            'fireplace' => isset($_POST['fireplace']) ? 1 : 0,
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'storage_room' => isset($_POST['storage_room']) ? 1 : 0,
        ];

        $settlement = trim((string) ($_POST['settlement'] ?? ''));
        if ($settlement === 'other') {
            $settlement = trim((string) ($_POST['settlement_other'] ?? ''));
        }
        if ($settlement === '') {
            $settlement = null;
        }

        Listing::update($id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'] ?? $listing['category'],
            'deal_type' => $_POST['deal_type'] ?? $listing['deal_type'],
            'region' => $_POST['region'] ?? null,
            'settlement' => $settlement,
            'address' => $_POST['address'] ?? null,
            'latitude' => !empty($_POST['latitude']) ? (float) $_POST['latitude'] : null,
            'longitude' => !empty($_POST['longitude']) ? (float) $_POST['longitude'] : null,
            'rooms' => !empty($_POST['rooms']) ? (int) $_POST['rooms'] : null,
            'bedrooms' => !empty($_POST['bedrooms']) ? (int) $_POST['bedrooms'] : null,
            'bathrooms' => !empty($_POST['bathrooms']) ? (int) $_POST['bathrooms'] : null,
            'area_sqm' => !empty($_POST['area_sqm']) ? (float) $_POST['area_sqm'] : null,
            'condition' => $_POST['condition'] ?? null,
            'price' => (float) $_POST['price'],
            'price_per_sqm' => $pricePerSqm,
            'extras' => json_encode($extras),
            'youtube_url' => $_POST['youtube_url'] ?? null,
            'expat_friendly' => isset($_POST['expat_friendly']) ? 1 : 0,
            'pets_allowed' => isset($_POST['pets_allowed']) ? 1 : 0,
            'title_en' => $_POST['title_en'] ?? null,
            'title_et' => $_POST['title_et'] ?? null,
            'title_ru' => $_POST['title_ru'] ?? null,
            'description_en' => $_POST['description_en'] ?? null,
            'description_et' => $_POST['description_et'] ?? null,
            'description_ru' => $_POST['description_ru'] ?? null,
            'status' => 'pending' // Re-approval needed after edit
        ]);

        // Handle image deletions
        $deletedImages = [];
        if (!empty($_POST['delete_images']) && is_array($_POST['delete_images'])) {
            foreach ($_POST['delete_images'] as $imageId) {
                $imageId = (int) $imageId;
                $image = ListingImage::find($imageId);

                // Verify image belongs to this listing
                if ($image && $image['listing_id'] == $id) {
                    $deletedImages[] = $imageId;

                    // Delete physical file
                    $filepath = __DIR__ . '/../public/uploads/listings/' . $id . '/' . $image['filename'];
                    if (file_exists($filepath)) {
                        unlink($filepath);
                    }

                    // Delete database record
                    ListingImage::delete($imageId);
                }
            }
        }

        // Handle primary image change (ignore if the chosen one was deleted)
        $primaryImageId = !empty($_POST['primary_image_id']) ? (int) $_POST['primary_image_id'] : 0;
        if ($primaryImageId && !in_array($primaryImageId, $deletedImages, true)) {
            $primaryImage = ListingImage::find($primaryImageId);
            if ($primaryImage) {
                ListingImage::setPrimary($primaryImageId, $id);
            }
        }

        // Handle new image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $uploader = new Uploader();
            $existingImages = ListingImage::getByListing($id);
            $existingCount = count($existingImages);
            $maxNew = max(0, 10 - $existingCount);
            $uploadedImages = $uploader->uploadMultipleWithStorage(
                $_FILES['images'],
                "listings/{$id}",
                'listing_',
                $maxNew
            );

            // Get current max sort order
            $maxOrder = 0;
            if (!empty($existingImages)) {
                $maxOrder = max(array_column($existingImages, 'sort_order'));
            }

            foreach ($uploadedImages as $index => $image) {
                ListingImage::create([
                    'listing_id' => $id,
                    'filename' => $image['filename'],
                    'original_filename' => $image['original_name'],
                    'sort_order' => $maxOrder + $index + 1,
                    'is_primary' => 0 // New images are never primary by default
                ]);
            }
        }

        // Ensure there is always a primary image if any images remain
        if (!ListingImage::getPrimaryImage($id)) {
            $remainingImages = ListingImage::getByListing($id);
            if (!empty($remainingImages)) {
                ListingImage::setPrimary((int) $remainingImages[0]['id'], $id);
            }
        }

        Flash::success(__('listing.updated_success'));
        header('Location: /my-listings');
        exit;
    }

    public function delete(string $id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing || ($listing['user_id'] != Auth::id() && !Auth::isAdmin())) {
            Flash::error(__('errors.access_denied'));
            exit;
        }

        // Delete images
        ListingImage::deleteByListing($id);

        // Delete listing
        Listing::delete($id);

        Flash::success(__('listing.deleted_success'));
        header('Location: /my-listings');
        exit;
    }

    public function toggleAvailability(string $id): void
    {
        Auth::requireAuth();

        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing || $listing['user_id'] != Auth::id()) {
            Flash::error(__('errors.access_denied'));
            exit;
        }

        Listing::toggleAvailability($id);

        Flash::success(__('listing.availability_updated'));
        header('Location: /my-listings');
        exit;
    }

    public function report(string $id): void
    {
        $id = (int) $id;
        $validator = new Validator($_POST, [
            'reason' => 'required|min:10'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /listings/{$id}");
            exit;
        }

        \Models\Report::submit(
            $id,
            $_POST['reason'],
            $_POST['email'] ?? null
        );

        Flash::success(__('listing.report_submitted'));
        header("Location: /listings/{$id}");
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
