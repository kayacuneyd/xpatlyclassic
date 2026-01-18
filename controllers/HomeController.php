<?php

namespace Controllers;

use Models\Listing;
use Models\User;
use Core\Cache;
use Core\Translation;

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

        $userStats = Cache::remember('home_user_stats', function() {
            return User::getStats();
        }, 900); // Cache for 15 minutes

        $locationStats = Cache::remember('home_location_stats', function() {
            $row = Listing::query(
                "SELECT COUNT(DISTINCT region) as regions
                 FROM listings
                 WHERE region IS NOT NULL AND TRIM(region) != ''"
            )->fetch();
            return $row ?: ['regions' => 0];
        }, 900);

        $newThisWeek = Cache::remember('home_new_this_week', function() {
            $since = date('Y-m-d H:i:s', strtotime('-7 days'));
            $row = Listing::query(
                "SELECT COUNT(*) as count
                 FROM listings
                 WHERE created_at >= ? AND status = 'active'",
                [$since]
            )->fetch();
            return (int) ($row['count'] ?? 0);
        }, 600);

        $heroStats = $this->buildHeroStats($stats, $userStats, $locationStats, $newThisWeek);

        $data = [
            'title' => __('home.title'),
            'featuredListings' => $featuredListings,
            'stats' => $stats,
            'heroStats' => $heroStats
        ];

        $this->view('home/index', $data);
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function buildHeroStats(array $listingStats, array $userStats, array $locationStats, int $newThisWeek): array
    {
        $metrics = [
            'active_listings' => [
                'value' => (int) ($listingStats['active_listings'] ?? 0),
                'label' => __('home.active_listings') ?? 'Active Listings',
            ],
            'expat_friendly' => [
                'value' => (int) ($listingStats['expat_friendly'] ?? 0),
                'label' => __('home.expat_friendly_listings') ?? 'Expat-Friendly Listings',
            ],
            'verified_users' => [
                'value' => (int) ($userStats['verified_users'] ?? 0),
                'label' => __('home.verified_users') ?? 'Verified Users',
            ],
            'cities_covered' => [
                'value' => (int) ($locationStats['regions'] ?? 0),
                'label' => __('home.cities_covered') ?? 'Cities Covered',
            ],
            'new_this_week' => [
                'value' => (int) $newThisWeek,
                'label' => __('home.new_this_week') ?? 'New This Week',
            ],
        ];

        $variants = [
            ['active_listings', 'expat_friendly', 'verified_users'],
            ['active_listings', 'cities_covered', 'new_this_week'],
            ['expat_friendly', 'verified_users', 'cities_covered'],
        ];

        $seed = date('Y-m-d') . '|' . Translation::getLocale();
        $variantIndex = abs(crc32($seed)) % count($variants);
        $selected = $variants[$variantIndex];

        $heroStats = [];
        foreach ($selected as $key) {
            if (!isset($metrics[$key])) {
                continue;
            }
            $metric = $metrics[$key];
            $heroStats[] = [
                'value' => $this->formatStatValue((int) $metric['value']),
                'label' => $metric['label'],
            ];
        }

        return $heroStats;
    }

    private function formatStatValue(int $value): string
    {
        $value = max(0, $value);
        $formatted = Translation::formatNumber($value);
        if ($value >= 100) {
            return $formatted . '+';
        }
        return $formatted;
    }
}
