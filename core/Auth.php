<?php

namespace Core;

use Models\User;

class Auth
{
    private static ?array $user = null;

    public static function attempt(string $email, string $password, bool $remember = false): bool
    {
        $user = User::findByEmail($email);

        if (function_exists('auth_log')) {
            auth_log('Login attempt: email=' . $email . ' | user_found=' . (int) (bool) $user);
        }

        if ($user && function_exists('auth_log')) {
            $hash = $user['password_hash'] ?? '';
            $isBcrypt = str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$');
            $hashPrefix = $hash ? substr($hash, 0, 7) : 'none';
            auth_log('Login hash info: email=' . $email . ' | user_id=' . $user['id'] . ' | updated_at=' . ($user['updated_at'] ?? 'none') . ' | hash_len=' . strlen($hash) . ' | bcrypt=' . (int) $isBcrypt . ' | hash_prefix=' . $hashPrefix);
        }

        if (!$user || !password_verify($password, $user['password_hash'])) {
            if (function_exists('auth_log')) {
                auth_log('Login failed: email=' . $email);
            }
            return false;
        }

        self::login($user, $remember);
        if (function_exists('auth_log')) {
            auth_log('Login success: email=' . $email . ' | user_id=' . $user['id'] . ' | session_id=' . session_id());
        }
        return true;
    }

    public static function login(array $user, bool $remember = false): void
    {
        // Set user data BEFORE regeneration to ensure it's preserved in the new session
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);

        // Regenerate session ID for security (preserves data)
        Session::regenerate();

        if ($remember) {
            $token = bin2hex(random_bytes(32));
            // Store remember token in database (you'd need a remember_tokens table)
            // For now, we'll use a long session lifetime
            ini_set('session.cookie_lifetime', 60 * 60 * 24 * 30); // 30 days
        }

        self::$user = $user;
        if (function_exists('auth_log')) {
            auth_log('Session set: user_id=' . $user['id'] . ' | role=' . $user['role'] . ' | session_id=' . session_id());
        }
    }

    public static function logout(): void
    {
        self::$user = null;
        Session::destroy();
    }

    public static function check(): bool
    {
        return Session::has('user_id');
    }

    public static function guest(): bool
    {
        return !self::check();
    }

    public static function user(): ?array
    {
        if (self::$user !== null) {
            return self::$user;
        }

        if (!self::check()) {
            return null;
        }

        $userId = Session::get('user_id');
        self::$user = User::find($userId);

        return self::$user;
    }

    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    public static function isAdmin(): bool
    {
        $user = self::user();
        return $user && in_array($user['role'], ['super_admin', 'moderator']);
    }

    public static function isSuperAdmin(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'super_admin';
    }

    public static function isOwner(): bool
    {
        $user = self::user();
        return $user && $user['role'] === 'owner';
    }

    public static function can(string $permission): bool
    {
        $user = self::user();
        if (!$user) {
            return false;
        }

        // Define permissions based on roles
        $permissions = [
            'super_admin' => ['*'], // All permissions
            'moderator' => [
                'edit_listings',
                'delete_listings',
                'change_listing_status',
                'edit_users',
                'view_logs',
                'handle_reports'
            ],
            'owner' => [
                'create_listing',
                'edit_own_listing',
                'delete_own_listing',
                'view_messages',
                'send_messages'
            ],
            'user' => [
                'save_searches',
                'save_favorites',
                'send_messages'
            ]
        ];

        $userPermissions = $permissions[$user['role']] ?? [];

        // Super admin has all permissions
        if (in_array('*', $userPermissions)) {
            return true;
        }

        return in_array($permission, $userPermissions);
    }

    public static function requireAuth(): void
    {
        if (self::guest()) {
            if (!Session::has('intended_url') && !empty($_SERVER['REQUEST_URI'])) {
                Session::set('intended_url', $_SERVER['REQUEST_URI']);
            }
            Flash::error('Please login to continue');
            if (function_exists('url')) {
                header('Location: ' . url('login'));
            } else {
                header('Location: /login');
            }
            exit;
        }
    }

    public static function requireRole(string|array $roles): void
    {
        self::requireAuth();

        $user = self::user();
        $roles = (array) $roles;

        if (!in_array($user['role'], $roles)) {
            http_response_code(403);
            die('Access denied');
        }
    }

    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
