<?php

namespace Models;

class AdminLog extends Model
{
    protected static string $table = 'admin_logs';

    public static function log(
        int $adminId,
        string $action,
        string $targetType,
        int $targetId,
        array $changes = [],
        ?string $reason = null
    ): int|string {
        return self::create([
            'admin_id' => $adminId,
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'changes' => json_encode($changes),
            'reason' => $reason,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    public static function getAll(array $filters = [], int $page = 1, int $perPage = 50): array
    {
        $offset = ($page - 1) * $perPage;

        $sql = "SELECT l.*, u.full_name as admin_name
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.admin_id = u.id
                WHERE 1=1";

        $params = [];

        if (!empty($filters['admin_id'])) {
            $sql .= " AND l.admin_id = ?";
            $params[] = $filters['admin_id'];
        }

        if (!empty($filters['action'])) {
            $sql .= " AND l.action = ?";
            $params[] = $filters['action'];
        }

        if (!empty($filters['target_type'])) {
            $sql .= " AND l.target_type = ?";
            $params[] = $filters['target_type'];
        }

        if (!empty($filters['date_from'])) {
            $sql .= " AND l.created_at >= ?";
            $params[] = $filters['date_from'];
        }

        if (!empty($filters['date_to'])) {
            $sql .= " AND l.created_at <= ?";
            $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY l.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = $offset;

        $result = self::query($sql, $params);
        return $result->fetchAll();
    }

    public static function getByTarget(string $targetType, int $targetId): array
    {
        $sql = "SELECT l.*, u.full_name as admin_name
                FROM " . self::$table . " l
                LEFT JOIN users u ON l.admin_id = u.id
                WHERE l.target_type = ? AND l.target_id = ?
                ORDER BY l.created_at DESC";

        $result = self::query($sql, [$targetType, $targetId]);
        return $result->fetchAll();
    }

    public static function exportToCsv(array $filters = []): string
    {
        $logs = self::getAll($filters, 1, 10000);

        $csv = "Timestamp,Admin,Action,Target Type,Target ID,Reason,IP Address\n";

        foreach ($logs as $log) {
            $csv .= sprintf(
                "%s,%s,%s,%s,%s,%s,%s\n",
                $log['created_at'],
                $log['admin_name'],
                $log['action'],
                $log['target_type'],
                $log['target_id'],
                $log['reason'] ?? '',
                $log['ip_address'] ?? ''
            );
        }

        return $csv;
    }
}
