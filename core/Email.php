<?php

namespace Core;

use Resend;
use Models\SiteSettings;

class Email
{
    private static ?Resend $client = null;

    /**
     * Get Resend client instance
     */
    private static function getClient(): ?Resend
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
        $verificationUrl = url('verify-email?token=' . $token);

        ob_start();
        require __DIR__ . '/../emails/verify-email.php';
        $html = ob_get_clean();

        return self::send($email, 'Verify your email address', $html);
    }
}
