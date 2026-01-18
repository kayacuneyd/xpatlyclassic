<?php

/**
 * Global Helper Functions
 * These functions are available throughout the application
 */

use Core\Translation;

if (!function_exists('__')) {
    /**
     * Translate a key
     */
    function __(string $key, array $replace = []): string
    {
        return Translation::get($key, $replace);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate a key (alias)
     */
    function trans(string $key, array $replace = []): string
    {
        return Translation::get($key, $replace);
    }
}

if (!function_exists('trans_choice')) {
    /**
     * Translate with pluralization
     */
    function trans_choice(string $key, int $count, array $replace = []): string
    {
        return Translation::choice($key, $count, $replace);
    }
}

if (!function_exists('redirect')) {
    /**
     * Redirect to a URL
     */
    function redirect(string $url): void
    {
        header("Location: $url");
        exit;
    }
}

if (!function_exists('old')) {
    /**
     * Get old input value
     */
    function old(string $key, mixed $default = ''): mixed
    {
        return $_SESSION['_old'][$key] ?? $default;
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get CSRF token
     */
    function csrf_token(): string
    {
        return Core\Session::getCsrfToken();
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate CSRF hidden input field
     */
    function csrf_field(): string
    {
        return '<input type="hidden" name="_token" value="' . csrf_token() . '">';
    }
}

if (!function_exists('asset')) {
    /**
     * Generate asset URL
     */
    function asset(string $path): string
    {
        return '/assets/' . ltrim($path, '/');
    }
}

if (!function_exists('url')) {
    /**
     * Generate URL with locale prefix
     */
    function url(string $path = ''): string
    {
        $locale = $_SESSION['locale'] ?? 'en';
        $path = ltrim($path, '/');

        // Avoid double locale when an explicit locale prefix is already present
        if (preg_match('#^(en|et|ru)/#', $path)) {
            return '/' . $path;
        }

        return "/{$locale}/{$path}";
    }
}

if (!function_exists('route')) {
    /**
     * Generate route URL (alias for url)
     */
    function route(string $path = ''): string
    {
        return url($path);
    }
}

if (!function_exists('e')) {
    /**
     * Escape HTML entities
     */
    function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
    }
}

if (!function_exists('dd')) {
    /**
     * Dump and die (for debugging)
     */
    function dd(...$vars): void
    {
        echo '<pre>';
        foreach ($vars as $var) {
            var_dump($var);
        }
        echo '</pre>';
        die(1);
    }
}

if (!function_exists('config')) {
    /**
     * Get configuration value
     */
    function config(string $key, mixed $default = null): mixed
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);

        $configPath = __DIR__ . "/../config/{$file}.php";
        if (!file_exists($configPath)) {
            return $default;
        }

        $config = require $configPath;

        foreach ($keys as $k) {
            if (!isset($config[$k])) {
                return $default;
            }
            $config = $config[$k];
        }

        return $config;
    }
}

if (!function_exists('settings')) {
    /**
     * Get site setting value
     */
    function settings(string $key, mixed $default = null): mixed
    {
        return Models\SiteSettings::get($key, $default);
    }
}

if (!function_exists('send_mail')) {
    /**
     * Send an email using PHPMailer and mail config
     */
    function send_mail(string $to, string $subject, string $htmlBody, ?string $replyTo = null, ?string $textBody = null): bool
    {
        $mailConfig = config('mail');
        try {
            $mail = new PHPMailer\PHPMailer\PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $mailConfig['host'];
            $mail->SMTPAuth = true;
            $mail->Username = $mailConfig['username'];
            $mail->Password = $mailConfig['password'];
            $mail->SMTPSecure = strtolower($mailConfig['encryption'] ?? 'tls') === 'ssl'
                ? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS
                : PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = $mailConfig['port'] ?? 587;

            $mail->setFrom($mailConfig['from']['address'], $mailConfig['from']['name']);
            $mail->addAddress($to);
            $reply = $replyTo ?: ($mailConfig['reply_to'] ?? null);
            if ($reply) {
                $mail->addReplyTo($reply);
            }

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $htmlBody;
            $mail->AltBody = $textBody ?: strip_tags($htmlBody);

            $mail->send();
            return true;
        } catch (\Throwable $e) {
            error_log('Mail send failed: ' . $e->getMessage());
            if (function_exists('auth_log')) {
                auth_log('Mail send failed: ' . $e->getMessage());
            }
            return false;
        }
    }
}

if (!function_exists('auth_log')) {
    /**
     * Write auth-related debug logs to storage/logs/auth.log
     */
    function auth_log(string $message): void
    {
        $logDir = __DIR__ . '/../storage/logs';
        if (!is_dir($logDir)) {
            @mkdir($logDir, 0770, true);
        }
        $line = '[' . date('Y-m-d H:i:s') . '] ' . $message . PHP_EOL;
        @file_put_contents($logDir . '/auth.log', $line, FILE_APPEND);
    }
}
