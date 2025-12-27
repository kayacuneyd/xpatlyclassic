<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Models\Listing;
use Models\Favorite;
use Models\Message;

class UserController
{
    public function dashboard(): void
    {
        Auth::requireAuth();

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
}
