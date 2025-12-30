<?php

namespace Controllers;

use Models\Listing;
use Core\Cache;

class SearchController
{
    public function index(): void
    {
        $filters = $this->buildFilters($_GET);
        $page = (int)($_GET['page'] ?? 1);
        $perPage = 20;

        // Get listings
        $listings = Listing::getActive($filters, $page, $perPage);
        $total = Listing::countActive($filters);
        $totalPages = ceil($total / $perPage);

        $data = [
            'title' => __('search.title'),
            'listings' => $listings,
            'filters' => $filters,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ];

        $this->view('search/index', $data);
    }

    private function buildFilters(array $params): array
    {
        $filters = [];

        if (!empty($params['region'])) {
            $filters['region'] = $params['region'];
        }

        if (!empty($params['settlement'])) {
            $filters['settlement'] = $params['settlement'];
        }

        if (!empty($params['category'])) {
            $filters['category'] = $params['category'];
        }

        if (!empty($params['deal_type'])) {
            $filters['deal_type'] = $params['deal_type'];
        }

        if (isset($params['expat_friendly'])) {
            $filters['expat_friendly'] = 1;
        }

        if (isset($params['pets_allowed'])) {
            $filters['pets_allowed'] = 1;
        }

        if (!empty($params['condition'])) {
            $filters['condition'] = $params['condition'];
        }

        if (!empty($params['rooms_min'])) {
            $filters['rooms_min'] = (int)$params['rooms_min'];
        }

        if (!empty($params['rooms_max'])) {
            $filters['rooms_max'] = (int)$params['rooms_max'];
        }

        if (!empty($params['price_min'])) {
            $filters['price_min'] = (float)$params['price_min'];
        }

        if (!empty($params['price_max'])) {
            $filters['price_max'] = (float)$params['price_max'];
        }

        if (!empty($params['area_min'])) {
            $filters['area_min'] = (float)$params['area_min'];
        }

        if (!empty($params['area_max'])) {
            $filters['area_max'] = (float)$params['area_max'];
        }

        if (!empty($params['floor_min'])) {
            $filters['floor_min'] = (int)$params['floor_min'];
        }

        if (!empty($params['floor_max'])) {
            $filters['floor_max'] = (int)$params['floor_max'];
        }

        if (!empty($params['year_min'])) {
            $filters['year_min'] = (int)$params['year_min'];
        }

        if (!empty($params['year_max'])) {
            $filters['year_max'] = (int)$params['year_max'];
        }

        if (!empty($params['energy_class'])) {
            $filters['energy_class'] = $params['energy_class'];
        }

        if (!empty($params['q'])) {
            $filters['q'] = trim($params['q']);
        }

        if (!empty($params['extras'])) {
            $filters['extras'] = is_array($params['extras']) ? $params['extras'] : [$params['extras']];
        }

        if (!empty($params['sort'])) {
            $filters['sort'] = $params['sort'];
        }

        // Map bounds filters for geographic search
        if (!empty($params['lat_min'])) {
            $filters['lat_min'] = (float)$params['lat_min'];
        }

        if (!empty($params['lat_max'])) {
            $filters['lat_max'] = (float)$params['lat_max'];
        }

        if (!empty($params['lng_min'])) {
            $filters['lng_min'] = (float)$params['lng_min'];
        }

        if (!empty($params['lng_max'])) {
            $filters['lng_max'] = (float)$params['lng_max'];
        }

        return $filters;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
