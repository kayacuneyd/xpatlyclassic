<?php

/**
 * Test Resend Email Configuration
 * Run: php test_resend_email.php
 */

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Load helper functions
require __DIR__ . '/core/helpers.php';

use Core\Email;

echo "=== Testing Resend Email Configuration ===\n\n";

// Check if email is enabled
if (!Email::isEnabled()) {
    echo "❌ Email is NOT enabled\n";
    echo "Please enable email in admin settings\n";
    exit(1);
}

echo "✅ Email is enabled\n";

// Send test email
echo "Sending test email to thexpatly@gmail.com...\n";

$sent = Email::send(
    'thexpatly@gmail.com',
    'Test Email from Xpatly',
    '<html>
    <body style="font-family: Arial, sans-serif; padding: 20px; background-color: #f4f4f4;">
        <div style="max-width: 600px; margin: 0 auto; background-color: white; padding: 30px; border-radius: 10px;">
            <h1 style="color: #7C3AED;">🎉 Resend is Working!</h1>
            <p>If you\'re reading this email, your Resend integration is configured correctly.</p>
            <p><strong>Configuration Details:</strong></p>
            <ul>
                <li>Email Service: Resend</li>
                <li>From: ' . (getenv('MAIL_FROM_NAME') ?: 'Xpatly') . '</li>
                <li>Status: ✅ Active</li>
            </ul>
            <p>You can now receive:</p>
            <ul>
                <li>✉️ New message notifications</li>
                <li>🔐 Password reset emails</li>
                <li>✅ Email verification</li>
            </ul>
            <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5; color: #666; font-size: 12px;">
                This is a test email from Xpatly.eu
            </p>
        </div>
    </body>
    </html>'
);

if ($sent) {
    echo "\n✅ SUCCESS! Test email sent successfully!\n";
    echo "Check your inbox: thexpatly@gmail.com\n";
    echo "(Also check spam folder if you don't see it)\n\n";
    echo "Next steps:\n";
    echo "1. Check your email\n";
    echo "2. Test message notifications by sending a message to a listing\n";
    echo "3. You're ready to go!\n";
} else {
    echo "\n❌ ERROR: Failed to send email\n";
    echo "Check the error logs for details\n";
    echo "Possible issues:\n";
    echo "- Invalid Resend API key\n";
    echo "- Domain not verified in Resend\n";
    echo "- Network connection issues\n";
}

echo "\n=== End of Test ===\n";
