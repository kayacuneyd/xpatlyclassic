<?php

namespace Controllers;

use Models\Listing;
use Core\Cache;

class HomeController
{
    public function index(): void
    {
        // Get featured listings (newest, expat-friendly)
        $featuredListings = Cache::remember('home_featured_listings', function() {
            return Listing::getActive(['sort' => 'relevance'], 1, 4);
        }, 300); // Cache for 5 minutes

        // Get stats
        $stats = Cache::remember('home_stats', function() {
            return Listing::getStats();
        }, 600); // Cache for 10 minutes

        $data = [
            'title' => __('home.title'),
            'featuredListings' => $featuredListings,
            'stats' => $stats
        ];

        $this->view('home/index', $data);
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
