<?php

namespace Models;

class User extends Model
{
    protected static string $table = 'users';

    public static function findByEmail(string $email): ?array
    {
        return self::first(['email' => $email]);
    }

    public static function findByGoogleId(string $googleId): ?array
    {
        return self::first(['google_id' => $googleId]);
    }

    public static function findByVerificationToken(string $token): ?array
    {
        return self::first(['verification_token' => $token]);
    }

    public static function findByResetToken(string $token): ?array
    {
        $sql = "SELECT * FROM " . self::$table . "
                WHERE reset_token = ? AND reset_expires > ?";

        $result = self::query($sql, [$token, date('Y-m-d H:i:s')]);
        return $result->fetch() ?: null;
    }

    public static function emailExists(string $email): bool
    {
        return self::findByEmail($email) !== null;
    }

    public static function verifyEmail(int $userId): int
    {
        return self::update($userId, [
            'email_verified' => 1,
            'is_verified' => 1,
            'verification_token' => null
        ]);
    }

    public static function verifyPhone(int $userId): int
    {
        $user = self::find($userId);
        $emailVerified = $user['email_verified'] ?? 0;

        return self::update($userId, [
            'phone_verified' => 1,
            'is_verified' => $emailVerified ? 1 : 0
        ]);
    }

    public static function createVerificationToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+24 hours'));

        self::update($userId, [
            'verification_token' => $token,
            'verification_expires' => $expires
        ]);

        return $token;
    }

    public static function createResetToken(int $userId): string
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

        self::update($userId, [
            'reset_token' => $token,
            'reset_expires' => $expires
        ]);

        return $token;
    }

    public static function resetPassword(int $userId, string $password): int
    {
        return self::update($userId, [
            'password_hash' => password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]),
            'reset_token' => null,
            'reset_expires' => null
        ]);
    }

    public static function isFullyVerified(int $userId): bool
    {
        $user = self::find($userId);
        return $user && $user['email_verified'] && $user['phone_verified'];
    }

    public static function search(string $query = '', array $filters = []): array
    {
        $sql = "SELECT u.id, u.full_name, u.email, u.phone, u.role, u.locale,
                       u.email_verified, u.phone_verified, u.created_at, u.updated_at,
                       COUNT(l.id) as listing_count
                FROM " . self::$table . " u
                LEFT JOIN listings l ON u.id = l.user_id
                WHERE 1=1";
        $params = [];

        if (!empty($query)) {
            $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR u.phone LIKE ?)";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
            $params[] = "%{$query}%";
        }

        if (!empty($filters['role'])) {
            $sql .= " AND u.role = ?";
            $params[] = $filters['role'];
        }

        if (isset($filters['verified']) && $filters['verified'] !== '') {
            $sql .= " AND u.email_verified = ? AND u.phone_verified = ?";
            $params[] = $filters['verified'];
            $params[] = $filters['verified'];
        }

        $sql .= " GROUP BY u.id, u.full_name, u.email, u.phone, u.role, u.locale,
                          u.email_verified, u.phone_verified, u.created_at, u.updated_at
                  ORDER BY u.created_at DESC";

        $result = self::query($sql, $params);
        return $result->fetchAll();
    }

    public static function getOwnerListingsCount(int $userId): int
    {
        $sql = "SELECT COUNT(*) as count FROM listings WHERE user_id = ?";
        $result = self::query($sql, [$userId]);
        $row = $result->fetch();
        return (int) ($row['count'] ?? 0);
    }

    /**
     * Check if user's email is verified
     */
    public static function isEmailVerified(int $userId): bool
    {
        $user = self::find($userId);
        return $user && (bool) ($user['email_verified'] ?? false);
    }

    /**
     * Get user statistics for admin dashboard
     */
    public static function getStats(): array
    {
        $sql = "SELECT
                COUNT(*) as total_users,
                SUM(CASE WHEN role = 'owner' THEN 1 ELSE 0 END) as total_owners,
                SUM(CASE WHEN role = 'user' THEN 1 ELSE 0 END) as regular_users,
                SUM(CASE WHEN email_verified = 1 AND phone_verified = 1 THEN 1 ELSE 0 END) as verified_users
                FROM " . self::$table;

        $result = self::query($sql);
        return $result->fetch();
    }
}
