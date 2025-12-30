<?php

namespace Models;

use Core\Database;
use PDO;

class BlogComment
{
    /**
     * Get approved comments for a post
     */
    public static function getByPost(int $postId): array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT bc.*, u.full_name as user_name
             FROM blog_comments bc
             LEFT JOIN users u ON bc.user_id = u.id
             WHERE bc.post_id = ? AND bc.status = 'approved'
             ORDER BY bc.created_at DESC",
            [$postId]
        );

        return $stmt->fetchAll();
    }

    /**
     * Get comment count for a post
     */
    public static function countByPost(int $postId): int
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT COUNT(*) as count FROM blog_comments
             WHERE post_id = ? AND status = 'approved'",
            [$postId]
        );
        return (int) $stmt->fetch()['count'];
    }

    /**
     * Create a new comment
     */
    public static function create(array $data): bool
    {
        $db = new Database();
        return $db->query(
            "INSERT INTO blog_comments (post_id, user_id, content, status, created_at, updated_at)
             VALUES (?, ?, ?, 'pending', CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)",
            [
                $data['post_id'],
                $data['user_id'],
                $data['content']
            ]
        ) !== false;
    }

    /**
     * Update comment status
     */
    public static function updateStatus(int $id, string $status): bool
    {
        $db = new Database();
        return $db->query(
            "UPDATE blog_comments SET status = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?",
            [$status, $id]
        ) !== false;
    }

    /**
     * Delete a comment
     */
    public static function delete(int $id): bool
    {
        $db = new Database();
        return $db->query("DELETE FROM blog_comments WHERE id = ?", [$id]) !== false;
    }

    /**
     * Get pending comments (for admin)
     */
    public static function getPending(int $limit = 50): array
    {
        $db = new Database();
        $stmt = $db->query(
            "SELECT bc.*, u.full_name as user_name, bp.title_en as post_title
             FROM blog_comments bc
             LEFT JOIN users u ON bc.user_id = u.id
             LEFT JOIN blog_posts bp ON bc.post_id = bp.id
             WHERE bc.status = 'pending'
             ORDER BY bc.created_at DESC
             LIMIT ?",
            [$limit]
        );

        return $stmt->fetchAll();
    }
}
