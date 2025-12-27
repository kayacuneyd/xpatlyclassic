<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); color: white; padding: 30px; text-align: center; border-radius: 10px 10px 0 0; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
        .button { display: inline-block; padding: 12px 30px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Welcome to Xpatly!</h1>
        </div>
        <div class="content">
            <h2>Hello <?= htmlspecialchars($name) ?>,</h2>

            <p>Thank you for joining <strong>Xpatly</strong> - Estonia's premier expat-friendly real estate platform!</p>

            <p>We're excited to have you on board. With Xpatly, you can:</p>

            <ul>
                <li>✓ Browse verified expat-friendly properties</li>
                <li>✓ Save your favorite listings</li>
                <li>✓ Set up email alerts for new properties</li>
                <li>✓ List your own property (free!)</li>
                <li>✓ Connect directly with property owners</li>
            </ul>

            <p><strong>Important:</strong> Please verify your email address to unlock all features:</p>

            <a href="<?= $verificationUrl ?>" class="button">Verify Email Address</a>

            <p>If the button doesn't work, copy and paste this link into your browser:</p>
            <p style="font-size: 12px; color: #6b7280; word-break: break-all;"><?= $verificationUrl ?></p>

            <h3>Getting Started</h3>
            <p>Here are some things you can do right away:</p>
            <ol>
                <li>Complete your profile verification (email + phone)</li>
                <li>Browse our latest listings</li>
                <li>Create your first listing (if you're a landlord)</li>
                <li>Save your favorite properties</li>
            </ol>

            <p>If you have any questions, feel free to reach out to us at <a href="mailto:info@xpatly.com">info@xpatly.com</a></p>

            <p>Welcome aboard!<br>
            The Xpatly Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Xpatly. All rights reserved.</p>
            <p>You're receiving this email because you signed up at xpatly.com</p>
        </div>
    </div>
</body>
</html>
