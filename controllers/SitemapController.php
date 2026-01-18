<?php

namespace Controllers;

use Models\Listing;

class SitemapController
{
    public function index(): void
    {
        header('Content-Type: application/xml; charset=utf-8');

        // Get all active listings
        $listings = Listing::where(['status' => 'active', 'is_available' => 1]);

        // Get all published blog posts (if BlogPost model exists)
        $blogPosts = [];
        if (class_exists('\Models\BlogPost')) {
            $blogPosts = \Models\BlogPost::getPublished();
        }

        echo '<?xml version="1.0" encoding="UTF-8"?>';
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"';
        echo ' xmlns:xhtml="http://www.w3.org/1999/xhtml">';

        // Homepage
        $this->addUrl('/', 1.0, 'daily', true);

        // Main pages
        $this->addUrl('/listings', 0.9, 'hourly', true);
        $this->addUrl('/about', 0.7, 'monthly', true);
        $this->addUrl('/contact', 0.6, 'monthly', true);
        $this->addUrl('/faq', 0.6, 'monthly', true);
        $this->addUrl('/blog', 0.7, 'weekly', true);

        // Legal pages
        $this->addUrl('/privacy', 0.3, 'yearly', true);
        $this->addUrl('/terms', 0.3, 'yearly', true);
        $this->addUrl('/imprint', 0.3, 'yearly', true);

        // Auth pages
        $this->addUrl('/login', 0.5, 'monthly', false);
        $this->addUrl('/register', 0.5, 'monthly', false);
        $this->addUrl('/listings/create', 0.8, 'monthly', true);

        // Active listings
        foreach ($listings as $listing) {
            $lastmod = date('Y-m-d', strtotime($listing['updated_at'] ?? $listing['created_at']));
            echo "\n    <url>";
            echo "\n        <loc>https://xpatly.eu/en/listings/{$listing['id']}</loc>";
            echo "\n        <lastmod>{$lastmod}</lastmod>";
            echo "\n        <changefreq>weekly</changefreq>";
            echo "\n        <priority>0.8</priority>";

            // Add multilingual versions
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/listings/{$listing['id']}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/listings/{$listing['id']}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/listings/{$listing['id']}\" />";
            echo "\n    </url>";
        }

        // Blog posts
        foreach ($blogPosts as $post) {
            $lastmod = date('Y-m-d', strtotime($post['updated_at'] ?? $post['created_at']));
            echo "\n    <url>";
            echo "\n        <loc>https://xpatly.eu/en/blog/{$post['slug']}</loc>";
            echo "\n        <lastmod>{$lastmod}</lastmod>";
            echo "\n        <changefreq>monthly</changefreq>";
            echo "\n        <priority>0.6</priority>";

            // Add multilingual versions
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en/blog/{$post['slug']}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et/blog/{$post['slug']}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru/blog/{$post['slug']}\" />";
            echo "\n    </url>";
        }

        echo "\n\n</urlset>";
        exit;
    }

    private function addUrl(string $path, float $priority, string $changefreq, bool $multilingual): void
    {
        echo "\n\n    <!-- " . ucfirst(trim($path, '/')) . " -->";
        echo "\n    <url>";
        echo "\n        <loc>https://xpatly.eu/en{$path}</loc>";
        echo "\n        <changefreq>{$changefreq}</changefreq>";
        echo "\n        <priority>{$priority}</priority>";

        if ($multilingual) {
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"en\" href=\"https://xpatly.eu/en{$path}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"et\" href=\"https://xpatly.eu/et{$path}\" />";
            echo "\n        <xhtml:link rel=\"alternate\" hreflang=\"ru\" href=\"https://xpatly.eu/ru{$path}\" />";
        }

        echo "\n    </url>";
    }
}
