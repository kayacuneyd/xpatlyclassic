<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Message on Xpatly</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f5f5f5; }
        .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #4F46E5 0%, #7C3AED 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px; }
        .message-box { background: #f8f9fa; border-left: 4px solid #4F46E5; padding: 20px; margin: 20px 0; border-radius: 4px; }
        .button { display: inline-block; padding: 14px 32px; background: #4F46E5; color: white !important; text-decoration: none; border-radius: 6px; font-weight: 600; font-size: 16px; }
        .button:hover { background: #4338CA; }
        .footer { background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Message on Xpatly</h1>
        </div>
        <div class="content">
            <p style="margin: 0 0 20px 0; font-size: 16px;">
                Hi <strong><?= htmlspecialchars($recipientName ?? 'there') ?></strong>,
            </p>

            <p style="margin: 0 0 20px 0; font-size: 16px;">
                You have a new message about your listing: <strong><?= htmlspecialchars($listingTitle) ?></strong>
            </p>

            <div class="message-box">
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">
                    <strong>From:</strong> <?= htmlspecialchars($senderName) ?>
                </p>
                <p style="margin: 0 0 10px 0; font-size: 14px; color: #666;">
                    <strong>Message:</strong>
                </p>
                <p style="margin: 0; font-size: 14px; white-space: pre-line;">
                    <?php
                    $messagePreview = strlen($message) > 200 ? substr($message, 0, 200) . '...' : $message;
                    echo nl2br(htmlspecialchars($messagePreview));
                    ?>
                </p>
            </div>

            <div style="text-align: center; margin: 30px 0;">
                <a href="<?= url('messages/conversation/' . $conversationId) ?>" class="button">
                    View and Reply on Xpatly
                </a>
            </div>

            <p style="margin: 20px 0 0 0; font-size: 14px; color: #666;">
                Respond quickly to increase your chances of finding the perfect tenant!
            </p>
        </div>
        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                This email was sent because you have a listing on Xpatly
            </p>
            <p style="margin: 0;">
                &copy; <?= date('Y') ?> Xpatly - Housing platform for expats in Estonia
            </p>
        </div>
    </div>
</body>
</html>
