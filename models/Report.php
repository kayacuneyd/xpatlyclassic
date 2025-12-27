<?php

namespace Models;

class Report extends Model
{
    protected static string $table = 'reports';

    public static function getPending(): array
    {
        $sql = "SELECT r.*, l.title as listing_title, l.id as listing_id, u.full_name as owner_name
                FROM " . self::$table . " r
                INNER JOIN listings l ON r.listing_id = l.id
                LEFT JOIN users u ON l.user_id = u.id
                WHERE r.status = 'pending'
                ORDER BY r.created_at ASC";

        $result = self::query($sql);
        return $result->fetchAll();
    }

    public static function getAll(array $filters = []): array
    {
        $sql = "SELECT r.*, l.title as listing_title, l.id as listing_id, u.full_name as owner_name,
                a.full_name as reviewed_by_name
                FROM " . self::$table . " r
                INNER JOIN listings l ON r.listing_id = l.id
                LEFT JOIN users u ON l.user_id = u.id
                LEFT JOIN users a ON r.reviewed_by = a.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND r.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY r.created_at DESC";

        $result = self::query($sql, $params);
        return $result->fetchAll();
    }

    public static function submit(int $listingId, string $reason, ?string $reporterEmail = null): int|string
    {
        return self::create([
            'listing_id' => $listingId,
            'reporter_email' => $reporterEmail,
            'reason' => $reason,
            'status' => 'pending'
        ]);
    }

    public static function review(int $id, int $adminId, string $status, ?string $notes = null): int
    {
        return self::update($id, [
            'status' => $status,
            'admin_notes' => $notes,
            'reviewed_by' => $adminId,
            'reviewed_at' => date('Y-m-d H:i:s')
        ]);
    }

    public static function countPending(): int
    {
        $sql = "SELECT COUNT(*) as count FROM " . self::$table . " WHERE status = 'pending'";
        $result = self::query($sql);
        $row = $result->fetch();
        return (int)($row['count'] ?? 0);
    }
}
