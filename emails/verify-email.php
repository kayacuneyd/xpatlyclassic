<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 30px; text-align: center; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; padding: 15px 40px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✉️ Verify Your Email</h1>
        </div>
        <div class="content">
            <h2>Hello <?= htmlspecialchars($name) ?>,</h2>

            <p>Thanks for signing up with Xpatly! To complete your registration and start using all features, please verify your email address.</p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= $verificationUrl ?>" class="button">Verify Email Address</a>
            </div>

            <p>Or copy and paste this link into your browser:</p>
            <p style="font-size: 12px; background: #f3f4f6; padding: 10px; border-radius: 5px; word-break: break-all;">
                <?= $verificationUrl ?>
            </p>

            <p><strong>This link will expire in 24 hours.</strong></p>

            <p>If you didn't create an account with Xpatly, you can safely ignore this email.</p>

            <p>Best regards,<br>
            The Xpatly Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Xpatly</p>
        </div>
    </div>
</body>
</html>
