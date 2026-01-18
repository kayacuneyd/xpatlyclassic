<?php

namespace Core;

class Session
{
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');

            $sessionPath = __DIR__ . '/../storage/sessions';
            if (!is_dir($sessionPath)) {
                @mkdir($sessionPath, 0770, true);
            }
            if (is_dir($sessionPath) && is_writable($sessionPath)) {
                session_save_path($sessionPath);
            } else {
                $fallback = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'xpatly_sessions';
                if (!is_dir($fallback)) {
                    @mkdir($fallback, 0770, true);
                }
                if (is_dir($fallback) && is_writable($fallback)) {
                    session_save_path($fallback);
                }
            }
            if (function_exists('auth_log')) {
                auth_log('Session save path: ' . session_save_path() . ' | HTTPS=' . (int) $isHttps);
            }

            $config = [
                'cookie_httponly' => true,
                'cookie_secure' => $isHttps, // only set Secure when on HTTPS to avoid dropped cookies
                'cookie_samesite' => 'Lax', // allow redirects/forms while limiting cross-site
                'cookie_path' => '/', // CRITICAL: Make cookie available site-wide across all paths
                'cookie_domain' => '', // Let browser determine domain automatically
                'use_strict_mode' => true,
            ];

            session_start($config);

            self::syncCsrfCookie($isHttps);

            // Regenerate session ID periodically for security
            if (!isset($_SESSION['created'])) {
                $_SESSION['created'] = time();
            } elseif (time() - $_SESSION['created'] > 1800) {
                session_regenerate_id(true);
                $_SESSION['created'] = time();
            }
        }
    }

    public static function set(string $key, mixed $value): void
    {
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        return $_SESSION[$key] ?? $default;
    }

    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    public static function destroy(): void
    {
        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
        $_SESSION['created'] = time();
    }

    public static function getCsrfToken(): string
    {
        if (!self::has('csrf_token')) {
            self::set('csrf_token', bin2hex(random_bytes(32)));
        }

        return self::get('csrf_token');
    }

    public static function getHoneypotFieldName(): string
    {
        if (!self::has('honeypot_field')) {
            self::set('honeypot_field', 'hp_' . bin2hex(random_bytes(6)));
        }

        return self::get('honeypot_field');
    }

    public static function verifyCsrfToken(string $token): bool
    {
        if (!empty($_COOKIE['csrf_token']) && hash_equals($_COOKIE['csrf_token'], $token)) {
            return true;
        }

        return hash_equals(self::getCsrfToken(), $token);
    }

    private static function syncCsrfCookie(bool $isHttps): void
    {
        $token = self::getCsrfToken();
        if (empty($token)) {
            return;
        }

        if (empty($_COOKIE['csrf_token']) || $_COOKIE['csrf_token'] !== $token) {
            setcookie('csrf_token', $token, [
                'expires' => 0,
                'path' => '/',
                'secure' => $isHttps,
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
    }
}
