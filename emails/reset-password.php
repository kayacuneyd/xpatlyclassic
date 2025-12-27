<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #ef4444; color: white; padding: 30px; text-align: center; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; padding: 15px 40px; background: #ef4444; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔒 Reset Your Password</h1>
        </div>
        <div class="content">
            <h2>Hello <?= htmlspecialchars($name) ?>,</h2>

            <p>We received a request to reset your password for your Xpatly account.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= $resetUrl ?>" class="button">Reset Password</a>
            </div>

            <p>Or copy and paste this link into your browser:</p>
            <p style="font-size: 12px; background: #f3f4f6; padding: 10px; border-radius: 5px; word-break: break-all;">
                <?= $resetUrl ?>
            </p>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong>
                <ul style="margin: 10px 0 0 0;">
                    <li>This link will expire in 1 hour</li>
                    <li>If you didn't request this reset, please ignore this email</li>
                    <li>Your password won't change until you create a new one</li>
                </ul>
            </div>

            <p>If you continue to have problems, please contact us at <a href="mailto:info@xpatly.com">info@xpatly.com</a></p>

            <p>Best regards,<br>
            The Xpatly Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Xpatly</p>
            <p>This is an automated email. Please do not reply.</p>
        </div>
    </div>
</body>
</html>
