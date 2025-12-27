<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; padding: 30px; text-align: center; }
        .content { background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; }
        .success-box { background: #d1fae5; border: 2px solid #10b981; border-radius: 10px; padding: 20px; margin: 20px 0; text-align: center; }
        .button { display: inline-block; padding: 15px 40px; background: #10b981; color: white; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .tips { background: #f3f4f6; border-radius: 5px; padding: 20px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🎉 Listing Approved!</h1>
        </div>
        <div class="content">
            <h2>Congratulations <?= htmlspecialchars($ownerName) ?>!</h2>

            <div class="success-box">
                <h2 style="color: #059669; margin-top: 0;">✅ Your listing is now LIVE!</h2>
                <p style="font-size: 18px; margin: 0;">It's visible to thousands of potential renters</p>
            </div>

            <p>Your listing has been reviewed and approved by our team:</p>

            <h3><?= htmlspecialchars($listingTitle) ?></h3>
            <p style="color: #6b7280;">
                📍 <?= htmlspecialchars($listingLocation) ?><br>
                💰 €<?= number_format($listingPrice, 0) ?>/month<br>
                📅 Published: <?= date('F j, Y') ?>
            </p>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= $listingUrl ?>" class="button">View Your Listing</a>
            </div>

            <div class="tips">
                <h3>📈 Tips to Get More Inquiries:</h3>
                <ul>
                    <li>✓ Respond quickly to messages (within 24 hours)</li>
                    <li>✓ Keep your listing updated with accurate information</li>
                    <li>✓ Upload high-quality photos (you can upload up to 40!)</li>
                    <li>✓ Write detailed descriptions about the property and neighborhood</li>
                    <li>✓ Enable the "Expat-Friendly" badge to reach more international tenants</li>
                </ul>
            </div>

            <p><strong>What happens next?</strong></p>
            <ul>
                <li>Your listing appears in search results immediately</li>
                <li>Interested renters can contact you directly via email</li>
                <li>You'll receive email notifications for all new messages</li>
                <li>You can edit or pause your listing anytime from your dashboard</li>
            </ul>

            <p>Good luck finding the perfect tenant!</p>

            <p>Best regards,<br>
            The Xpatly Team</p>
        </div>
        <div class="footer">
            <p>&copy; <?= date('Y') ?> Xpatly</p>
            <p><a href="<?= $baseUrl ?>/my-listings">Manage your listings</a> | <a href="<?= $baseUrl ?>/messages">View messages</a></p>
        </div>
    </div>
</body>
</html>
