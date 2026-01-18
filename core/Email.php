<?php

namespace Core;

use Resend;
use Models\SiteSettings;

class Email
{
    private static $client = null;

    /**
     * Get Resend client instance
     */
    private static function getClient()
    {
        if (self::$client === null) {
            $apiKey = SiteSettings::get('resend_api_key');

            if (!$apiKey || empty($apiKey)) {
                return null;
            }

            self::$client = Resend::client($apiKey);
        }

        return self::$client;
    }

    /**
     * Check if email sending is enabled
     */
    public static function isEnabled(): bool
    {
        return SiteSettings::get('email_enabled') === '1' &&
               !empty(SiteSettings::get('resend_api_key'));
    }

    /**
     * Send email via Resend API
     */
    public static function send(string $to, string $subject, string $html): bool
    {
        if (!self::isEnabled()) {
            // Fallback to SMTP if configured
            if (function_exists('send_mail')) {
                return send_mail($to, $subject, $html);
            }
            error_log('Email sending is disabled or API key not configured');
            return false;
        }

        $client = self::getClient();
        if (!$client) {
            error_log('Resend client not initialized');
            return false;
        }

        try {
            $fromName = SiteSettings::get('email_from_name', 'Xpatly');
            $fromAddress = SiteSettings::get('email_from_address', 'noreply@xpatly.com');
            $from = $fromName . ' <' . $fromAddress . '>';

            $client->emails->send([
                'from' => $from,
                'to' => [$to],
                'subject' => $subject,
                'html' => $html
            ]);

            return true;
        } catch (\Exception $e) {
            error_log('Email send error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send verification email to new user
     */
    public static function sendVerificationEmail(string $email, string $token, string $userName): bool
    {
        $baseUrl = $_ENV['APP_URL'] ?? (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : '');
        $baseUrl = rtrim($baseUrl, '/');
        $baseUrl = preg_replace('#/(en|et|ru)$#', '', $baseUrl);

        $verificationUrl = $baseUrl . url('verify-email?token=' . $token);
        $name = (string) $userName;

        ob_start();
        require __DIR__ . '/../emails/verify-email.php';
        $html = ob_get_clean();

        return self::send($email, 'Verify your email address', $html);
    }
}
