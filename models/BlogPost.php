<?php

namespace Models;

use Core\Database;
use Core\Translation;
use PDO;

class BlogPost
{
    /**
     * Get all published posts with locale-aware content
     */
    public static function getPublished(int $limit = 10, int $offset = 0): array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT * FROM blog_posts 
             WHERE status = 'published' 
             ORDER BY published_at DESC 
             LIMIT ? OFFSET ?",
            [$limit, $offset]
        );

        $posts = $stmt->fetchAll();
        return array_map([self::class, 'applyLocale'], $posts);
    }

    /**
     * Get total count of published posts
     */
    public static function countPublished(): int
    {
        $db = new Database();
        $stmt = $db->query("SELECT COUNT(*) as count FROM blog_posts WHERE status = 'published'");
        return (int) $stmt->fetch()['count'];
    }

    /**
     * Find post by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT bp.*, u.full_name as author_name 
             FROM blog_posts bp 
             LEFT JOIN users u ON bp.author_id = u.id 
             WHERE bp.slug = ?",
            [$slug]
        );

        $post = $stmt->fetch();
        return $post ? self::applyLocale($post) : null;
    }

    /**
     * Find post by ID
     */
    public static function find(int $id): ?array
    {
        $db = new Database();
        $stmt = $db->query("SELECT * FROM blog_posts WHERE id = ?", [$id]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Get all posts for admin
     */
    public static function getAll(): array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT bp.*, u.full_name as author_name 
             FROM blog_posts bp 
             LEFT JOIN users u ON bp.author_id = u.id 
             ORDER BY bp.created_at DESC"
        );
        return $stmt->fetchAll();
    }

    /**
     * Create new post
     */
    public static function create(array $data): int
    {
        $db = new Database();
        return (int) $db->insert('blog_posts', $data);
    }

    /**
     * Update post
     */
    public static function update(int $id, array $data): bool
    {
        $db = new Database();
        return $db->update('blog_posts', $data, ['id' => $id]) > 0;
    }

    /**
     * Delete post
     */
    public static function delete(int $id): bool
    {
        $db = new Database();
        return $db->delete('blog_posts', ['id' => $id]) > 0;
    }

    /**
     * Increment view count
     */
    public static function incrementViews(int $id): void
    {
        $db = new Database();
        $db->query("UPDATE blog_posts SET views = views + 1 WHERE id = ?", [$id]);
    }

    /**
     * Generate unique slug from title
     */
    public static function generateSlug(string $title, ?int $excludeId = null): string
    {
        // Convert to lowercase and replace spaces with hyphens
        $slug = strtolower(trim($title));
        $slug = preg_replace('/[^a-z0-9\-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');

        // Check for uniqueness
        $db = new Database();
        $baseSlug = $slug;
        $counter = 1;

        while (true) {
            $query = "SELECT COUNT(*) as count FROM blog_posts WHERE slug = ?";
            $params = [$slug];

            if ($excludeId !== null) {
                $query .= " AND id != ?";
                $params[] = $excludeId;
            }

            $stmt = $db->query($query, $params);
            if ($stmt->fetch()['count'] == 0) {
                break;
            }

            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Apply locale to post (select correct language fields)
     */
    public static function applyLocale(array $post): array
    {
        $locale = Translation::getLocale();

        // Get localized fields with fallback to English
        $post['title'] = $post["title_{$locale}"] ?? $post['title_en'] ?? '';
        $post['content'] = $post["content_{$locale}"] ?? $post['content_en'] ?? '';
        $post['meta_title'] = $post["meta_title_{$locale}"] ?? $post['meta_title_en'] ?? $post['title'];
        $post['meta_description'] = $post["meta_description_{$locale}"] ?? $post['meta_description_en'] ?? '';

        return $post;
    }

    /**
     * Get recent posts for sidebar/homepage
     */
    public static function getRecent(int $limit = 5): array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT id, slug, title_en, title_et, title_ru, featured_image, published_at 
             FROM blog_posts 
             WHERE status = 'published' 
             ORDER BY published_at DESC 
             LIMIT ?",
            [$limit]
        );

        $posts = $stmt->fetchAll();
        return array_map([self::class, 'applyLocale'], $posts);
    }
}
