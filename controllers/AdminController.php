<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Models\Listing;
use Models\User;
use Models\Report;
use Models\AdminLog;

class AdminController
{
    public function __construct()
    {
        Auth::requireRole(['super_admin', 'moderator']);
    }

    public function dashboard(): void
    {
        $listingStats = Listing::getStats();
        $userStats = User::getStats();

        // Merge both stats arrays
        $stats = array_merge($listingStats, $userStats);

        $pendingListings = count(Listing::getPending());
        $pendingReports = Report::countPending();

        $this->view('admin/dashboard', [
            'title' => 'Admin Dashboard',
            'stats' => $stats,
            'pendingListings' => $pendingListings,
            'pendingReports' => $pendingReports
        ]);
    }

    public function listings(): void
    {
        $query = $_GET['q'] ?? '';
        $filters = [
            'status' => $_GET['status'] ?? '',
            'expat_friendly' => $_GET['expat_friendly'] ?? ''
        ];

        $listings = Listing::adminSearch($query, $filters);

        $this->view('admin/listings', [
            'title' => 'Manage Listings',
            'listings' => $listings,
            'query' => $query,
            'filters' => $filters
        ]);
    }

    public function editListing(string $id): void
    {
        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing) {
            Flash::error('Listing not found');
            header('Location: /admin/listings');
            exit;
        }

        $images = \Models\ListingImage::getByListing($id);
        $extras = json_decode($listing['extras'] ?? '{}', true);

        $this->view('admin/edit-listing', [
            'title' => 'Edit Listing',
            'listing' => $listing,
            'images' => $images,
            'extras' => $extras
        ]);
    }

    public function updateListing(string $id): void
    {
        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing) {
            Flash::error('Listing not found');
            header('Location: /admin/listings');
            exit;
        }

        $oldData = $listing;

        $validator = new Validator($_POST, [
            'title' => 'required|no_discrimination',
            'description' => 'required|no_discrimination',
            'price' => 'required|numeric'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header("Location: /admin/listings/{$id}/edit");
            exit;
        }

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

        $newData = [
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'price' => (float) $_POST['price'],
            'price_per_sqm' => $pricePerSqm,
            'extras' => json_encode($extras),
            'expat_friendly' => isset($_POST['expat_friendly']) ? 1 : 0,
            'pets_allowed' => isset($_POST['pets_allowed']) ? 1 : 0,
        ];

        Listing::update($id, $newData);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'edit_listing',
            'listing',
            $id,
            ['old' => $oldData, 'new' => $newData]
        );

        Flash::success('Listing updated successfully');
        header('Location: /admin/listings');
        exit;
    }

    public function deleteListing(string $id): void
    {
        $id = (int) $id;
        $listing = Listing::find($id);

        if (!$listing) {
            Flash::error('Listing not found');
            header('Location: /admin/listings');
            exit;
        }

        $reason = $_POST['reason'] ?? '';

        if (empty($reason)) {
            Flash::error('Deletion reason is required');
            header('Location: /admin/listings');
            exit;
        }

        // Delete images
        \Models\ListingImage::deleteByListing($id);

        // Delete listing
        Listing::delete($id);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'delete_listing',
            'listing',
            $id,
            ['listing' => $listing],
            $reason
        );

        Flash::success('Listing deleted successfully');
        header('Location: /admin/listings');
        exit;
    }

    public function changeStatus(string $id): void
    {
        $id = (int) $id;
        $status = $_POST['status'] ?? '';

        $validStatuses = ['pending', 'active', 'paused', 'archived'];
        if (!in_array($status, $validStatuses)) {
            Flash::error('Invalid status');
            header('Location: /admin/listings');
            exit;
        }

        Listing::changeStatus($id, $status);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'change_listing_status',
            'listing',
            $id,
            ['status' => $status]
        );

        Flash::success('Listing status updated');
        header('Location: /admin/listings');
        exit;
    }

    public function users(): void
    {
        $query = $_GET['q'] ?? '';
        $filters = [
            'role' => $_GET['role'] ?? '',
            'verified' => $_GET['verified'] ?? ''
        ];

        $users = User::search($query, $filters);

        $this->view('admin/users', [
            'title' => 'Manage Users',
            'users' => $users,
            'query' => $query,
            'filters' => $filters
        ]);
    }

    public function editUser(string $id): void
    {
        if (!Auth::isSuperAdmin()) {
            Flash::error('Access denied');
            header('Location: /admin/users');
            exit;
        }

        $id = (int) $id;
        $user = User::find($id);

        if (!$user) {
            Flash::error('User not found');
            header('Location: /admin/users');
            exit;
        }

        $this->view('admin/edit-user', [
            'title' => 'Edit User',
            'user' => $user
        ]);
    }

    public function updateUser(string $id): void
    {
        if (!Auth::isSuperAdmin()) {
            Flash::error('Access denied');
            header('Location: /admin/users');
            exit;
        }

        $id = (int) $id;
        $user = User::find($id);

        if (!$user) {
            Flash::error('User not found');
            header('Location: /admin/users');
            exit;
        }

        $oldData = $user;

        $newData = [
            'full_name' => $_POST['full_name'],
            'phone' => $_POST['phone'],
            'locale' => $_POST['locale'],
            'role' => $_POST['role'],
            'email_verified' => isset($_POST['email_verified']) ? 1 : 0,
            'phone_verified' => isset($_POST['phone_verified']) ? 1 : 0,
        ];

        User::update($id, $newData);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'edit_user',
            'user',
            $id,
            ['old' => $oldData, 'new' => $newData]
        );

        Flash::success('User updated successfully');
        header('Location: /admin/users');
        exit;
    }

    public function deleteUser(string $id): void
    {
        if (!Auth::isSuperAdmin()) {
            Flash::error('Access denied');
            header('Location: /admin/users');
            exit;
        }

        $id = (int) $id;
        $user = User::find($id);

        if (!$user) {
            Flash::error('User not found');
            header('Location: /admin/users');
            exit;
        }

        // Don't allow deleting yourself
        if ($id === Auth::id()) {
            Flash::error('You cannot delete yourself');
            header('Location: /admin/users');
            exit;
        }

        User::delete($id);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'delete_user',
            'user',
            $id,
            ['user' => $user]
        );

        Flash::success('User deleted successfully');
        header('Location: /admin/users');
        exit;
    }

    public function reports(): void
    {
        $filters = ['status' => $_GET['status'] ?? ''];
        $reports = Report::getAll($filters);

        $this->view('admin/reports', [
            'title' => 'Manage Reports',
            'reports' => $reports,
            'filters' => $filters
        ]);
    }

    public function reviewReport(string $id): void
    {
        $id = (int) $id;
        $status = $_POST['status'] ?? '';
        $notes = $_POST['notes'] ?? '';

        $validStatuses = ['reviewed', 'dismissed'];
        if (!in_array($status, $validStatuses)) {
            Flash::error('Invalid status');
            header('Location: /admin/reports');
            exit;
        }

        Report::review($id, Auth::id(), $status, $notes);

        // Log the action
        AdminLog::log(
            Auth::id(),
            'review_report',
            'report',
            $id,
            ['status' => $status, 'notes' => $notes]
        );

        Flash::success('Report reviewed');
        header('Location: /admin/reports');
        exit;
    }

    public function settings(): void
    {
        if (!Auth::isSuperAdmin()) {
            Flash::error('Access denied');
            header('Location: /admin');
            exit;
        }

        $settings = \Models\SiteSettings::getAllWithMeta();

        $this->view('admin/settings', [
            'title' => 'Site Settings',
            'settings' => $settings
        ]);
    }

    public function updateSettings(): void
    {
        if (!Auth::isSuperAdmin()) {
            Flash::error('Access denied');
            header('Location: /admin');
            exit;
        }

        // Handle file uploads (logo, icon)
        $uploadDir = __DIR__ . '/../public/uploads/settings';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Process logo upload
        if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] === UPLOAD_ERR_OK) {
            $fileName = 'logo_' . time() . '_' . $_FILES['site_logo']['name'];
            $filePath = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($_FILES['site_logo']['tmp_name'], $filePath)) {
                \Models\SiteSettings::set('site_logo', '/uploads/settings/' . $fileName, 'image');
            }
        }

        // Process icon upload
        if (isset($_FILES['site_icon']) && $_FILES['site_icon']['error'] === UPLOAD_ERR_OK) {
            $fileName = 'icon_' . time() . '_' . $_FILES['site_icon']['name'];
            $filePath = $uploadDir . '/' . $fileName;

            if (move_uploaded_file($_FILES['site_icon']['tmp_name'], $filePath)) {
                \Models\SiteSettings::set('site_icon', '/uploads/settings/' . $fileName, 'image');
            }
        }

        // Update text settings
        $textSettings = ['site_name', 'site_tagline', 'contact_email', 'contact_phone'];
        foreach ($textSettings as $key) {
            if (isset($_POST[$key])) {
                \Models\SiteSettings::set($key, $_POST[$key], 'text');
            }
        }

        // Update textarea settings
        $textareaSettings = ['meta_description', 'meta_keywords'];
        foreach ($textareaSettings as $key) {
            if (isset($_POST[$key])) {
                \Models\SiteSettings::set($key, $_POST[$key], 'textarea');
            }
        }

        // Update R2 Storage settings
        $r2Settings = ['r2_access_key_id', 'r2_secret_access_key', 'r2_bucket_name', 'r2_endpoint', 'r2_public_url'];
        foreach ($r2Settings as $key) {
            if (isset($_POST[$key])) {
                \Models\SiteSettings::set($key, $_POST[$key], 'text');
            }
        }

        // Update Email settings
        \Models\SiteSettings::set('email_enabled', isset($_POST['email_enabled']) ? '1' : '0', 'text');

        $emailSettings = ['resend_api_key', 'email_from_address', 'email_from_name'];
        foreach ($emailSettings as $key) {
            if (isset($_POST[$key])) {
                \Models\SiteSettings::set($key, $_POST[$key], 'text');
            }
        }

        // Log the action
        AdminLog::log(
            Auth::id(),
            'update_settings',
            'site_settings',
            0,
            ['settings' => $_POST]
        );

        Flash::success('Settings updated successfully');
        header('Location: /admin/settings');
        exit;
    }

    public function logs(): void
    {
        $filters = [
            'admin_id' => $_GET['admin_id'] ?? '',
            'action' => $_GET['action'] ?? '',
            'date_from' => $_GET['date_from'] ?? '',
            'date_to' => $_GET['date_to'] ?? ''
        ];

        $page = (int) ($_GET['page'] ?? 1);
        $logs = AdminLog::getAll($filters, $page);

        $this->view('admin/logs', [
            'title' => 'Activity Logs',
            'logs' => $logs,
            'filters' => $filters,
            'page' => $page
        ]);
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
