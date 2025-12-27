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

        if (!$listing || ($listing['status'] !== 'active' && !Auth::isAdmin())) {
            http_response_code(404);
            require __DIR__ . '/../views/errors/404.php';
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
        Auth::requireRole(['owner', 'super_admin']);

        // Check if user is verified
        if (!User::isFullyVerified(Auth::id()) && !Auth::isSuperAdmin()) {
            Flash::error(__('listing.verification_required'));
            header('Location: /dashboard');
            exit;
        }

        $this->view('listings/create', ['title' => __('listing.create')]);
    }

    public function store(): void
    {
        Auth::requireRole(['owner', 'super_admin']);

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
            'settlement' => 'required',
            'address' => 'required',
            'rooms' => 'required|integer|min_value:1',
            'area_sqm' => 'required|numeric|min_value:1|max_value:500',
            'price' => 'required|numeric|min_value:1',
            'condition' => 'required|in:new_development,good,renovated,needs_renovation'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /listings/create');
            exit;
        }

        // Base fields derive from English as primary
        $_POST['title'] = $_POST['title_en'];
        $_POST['description'] = $_POST['description_en'];

        $duplicate = Listing::checkDuplicate($_POST['address'], Auth::id());
        if ($duplicate) {
            Flash::warning(__('listing.duplicate_warning'));
        }

        // Calculate price per sqm
        $pricePerSqm = $_POST['price'] / $_POST['area_sqm'];

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

        // Create listing
        $listingId = Listing::create([
            'user_id' => Auth::id(),
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category'],
            'deal_type' => $_POST['deal_type'],
            'region' => $_POST['region'],
            'settlement' => $_POST['settlement'],
            'address' => $_POST['address'],
            'latitude' => $_POST['latitude'] ?? null,
            'longitude' => $_POST['longitude'] ?? null,
            'rooms' => (int)$_POST['rooms'],
            'area_sqm' => (float)$_POST['area_sqm'],
            'price' => (float)$_POST['price'],
            'price_per_sqm' => $pricePerSqm,
            'condition' => $_POST['condition'],
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
            'status' => 'pending'
        ]);

        // Handle image uploads
        if (!empty($_FILES['images']['name'][0])) {
            $uploader = new Uploader();
            $uploadedImages = $uploader->uploadMultiple(
                $_FILES['images'],
                "public/uploads/listings/{$listingId}",
                'listing_'
            );

            foreach ($uploadedImages as $index => $image) {
                ListingImage::create([
                    'listing_id' => $listingId,
                    'filename' => $image['filename'],
                    'original_filename' => $image['original_name'],
                    'sort_order' => $index,
                    'is_primary' => $index === 0 ? 1 : 0
                ]);
            }
        }

        Flash::success(__('listing.created_success'));
        header('Location: /my-listings');
        exit;
    }

    public function myListings(): void
    {
        Auth::requireAuth();

        $listings = Listing::getByUser(Auth::id());

        $this->view('listings/my-listings', [
            'title' => __('listing.my_listings'),
            'listings' => $listings
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

        Listing::update($id, [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'price' => (float)$_POST['price'],
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
