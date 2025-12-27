<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 30px; text-align: center; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
        .message-box { background: #f3f4f6; border-left: 4px solid #10b981; padding: 20px; margin: 20px 0; }
        .button { display: inline-block; padding: 15px 40px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .listing-card { border: 1px solid #e5e7eb; border-radius: 5px; padding: 15px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✉️ New Message Received!</h1>
        </div>
        <div class="content">
            <h2>Hello <?= htmlspecialchars($ownerName) ?>,</h2>

            <p>Great news! You've received a new inquiry about your listing.</p>

            <div class="listing-card">
                <h3 style="margin-top: 0;"><?= htmlspecialchars($listingTitle) ?></h3>
                <p style="color: #6b7280; margin: 0;">
                    📍 <?= htmlspecialchars($listingLocation) ?><br>
                    💰 €<?= number_format($listingPrice, 0) ?>/month
                </p>
            </div>

            <h3>Message from <?= htmlspecialchars($senderName ?: $senderEmail) ?>:</h3>
            <div class="message-box">
                <p style="white-space: pre-line; margin: 0;"><?= htmlspecialchars($message) ?></p>
            </div>

            <p><strong>Contact Information:</strong></p>
            <ul>
                <?php if ($senderName): ?>
                    <li>Name: <?= htmlspecialchars($senderName) ?></li>
                <?php endif; ?>
                <li>Email: <a href="mailto:<?= htmlspecialchars($senderEmail) ?>"><?= htmlspecialchars($senderEmail) ?></a></li>
            </ul>

            <div style="text-align: center; margin: 30px 0;">
                <a href="mailto:<?= htmlspecialchars($senderEmail) ?>?subject=Re: <?= urlencode($listingTitle) ?>" class="button">
                    Reply via Email
                </a>
            </div>

            <p>Or view all your messages on Xpatly:</p>
            <p style="text-align: center;">
                <a href="<?= $baseUrl ?>/messages" style="color: #2563eb;">View Messages</a>
            </p>

            <p><strong>💡 Tip:</strong> Respond quickly to increase your chances of securing a tenant!</p>

            <p>Best regards,<br>
            The Xpatly Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Xpatly</p>
            <p>Reply to this email or <a href="<?= $baseUrl ?>/messages">manage your messages</a></p>
        </div>
    </div>
</body>
</html>
