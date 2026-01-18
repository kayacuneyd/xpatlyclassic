<?php

// Support both MAIL_* and SMTP_* env keys
$env = $_ENV;

$host = $env['MAIL_HOST'] ?? $env['SMTP_HOST'] ?? 'smtp.hostinger.com';
$port = (int) ($env['MAIL_PORT'] ?? $env['SMTP_PORT'] ?? 587);
$user = $env['MAIL_USERNAME'] ?? $env['SMTP_USER'] ?? '';
$pass = $env['MAIL_PASSWORD'] ?? $env['SMTP_PASS'] ?? '';
$secure = $env['MAIL_ENCRYPTION'] ?? $env['SMTP_SECURE'] ?? 'tls';
$from = $env['MAIL_FROM_ADDRESS'] ?? $env['MAIL_FROM'] ?? ($env['SMTP_USER'] ?? 'noreply@xpatly.com');
$fromName = $env['MAIL_FROM_NAME'] ?? 'Xpatly';
$replyTo = $env['MAIL_REPLY_TO'] ?? $from;

return [
    'host' => $host,
    'port' => $port,
    'username' => $user,
    'password' => $pass,
    'encryption' => $secure,
    'from' => [
        'address' => $from,
        'name' => $fromName
    ],
    'reply_to' => $replyTo
];
