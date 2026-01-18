<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Models\Listing;
use Models\Favorite;
use Models\Message;
use Models\User;

class UserController
{
    public function dashboard(): void
    {
        Auth::requireAuth();
        $this->disableCache();

        $user = Auth::user();

        // Get user's listings count
        $listingsCount = count(Listing::getByUser(Auth::id()));

        // Get favorites count
        $favoritesCount = Favorite::count(Auth::id());

        // Get unread messages count
        $unreadMessages = Message::getUnreadCount(Auth::id());

        $data = [
            'title' => __('user.dashboard'),
            'user' => $user,
            'listingsCount' => $listingsCount,
            'favoritesCount' => $favoritesCount,
            'unreadMessages' => $unreadMessages
        ];

        $this->view('user/dashboard', $data);
    }

    public function favorites(): void
    {
        Auth::requireAuth();

        $favorites = Favorite::getByUser(Auth::id());

        $this->view('user/favorites', [
            'title' => __('user.favorites'),
            'favorites' => $favorites
        ]);
    }

    public function updateRole(): void
    {
        Auth::requireAuth();

        $currentRole = Auth::user()['role'] ?? 'user';
        if (!in_array($currentRole, ['user', 'owner'], true)) {
            Flash::error('Role cannot be changed for this account.');
            header('Location: ' . url('dashboard'));
            exit;
        }

        $role = $_POST['role'] ?? '';
        if (!in_array($role, ['user', 'owner'], true)) {
            Flash::error('Invalid role selection.');
            header('Location: ' . url('dashboard'));
            exit;
        }

        User::update(Auth::id(), ['role' => $role]);
        Flash::success('Account type updated.');
        header('Location: ' . url('dashboard'));
        exit;
    }

    public function toggleFavorite(int $listingId): void
    {
        Auth::requireAuth();

        $added = Favorite::toggle(Auth::id(), $listingId);

        if ($added) {
            Flash::success(__('user.favorite_added'));
        } else {
            Flash::success(__('user.favorite_removed'));
        }

        // Return JSON if AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'added' => $added]);
            exit;
        }

        header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/listings/' . $listingId));
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function disableCache(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
