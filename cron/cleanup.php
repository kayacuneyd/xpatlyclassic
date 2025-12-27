<?php

/**
 * Database Cleanup Cron Job
 * Run daily at 2:00 AM: 0 2 * * * php /path/to/cron/cleanup.php
 */

require __DIR__ . '/../vendor/autoload.php';

if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

use Core\Database;

echo "[" . date('Y-m-d H:i:s') . "] Starting cleanup tasks...\n";

$db = new Database();

// 1. Clean expired password reset tokens
$sql = "UPDATE users SET reset_token = NULL, reset_expires = NULL
        WHERE reset_expires < ?";
$stmt = $db->query($sql, [date('Y-m-d H:i:s')]);
$count = $stmt->rowCount();
echo "✓ Cleaned $count expired password reset tokens\n";

// 2. Delete old unverified accounts (older than 30 days)
$sql = "DELETE FROM users
        WHERE email_verified = 0
        AND created_at < ?";
$stmt = $db->query($sql, [date('Y-m-d H:i:s', strtotime('-30 days'))]);
$count = $stmt->rowCount();
echo "✓ Deleted $count old unverified accounts\n";

// 3. Clean old cache files
$cacheDir = __DIR__ . '/../storage/cache/';
$files = glob($cacheDir . '*.cache');
$deletedFiles = 0;
foreach ($files as $file) {
    if (filemtime($file) < strtotime('-7 days')) {
        unlink($file);
        $deletedFiles++;
    }
}
echo "✓ Deleted $deletedFiles old cache files\n";

// 4. Clean old session files
$sessionDir = __DIR__ . '/../storage/sessions/';
if (is_dir($sessionDir)) {
    $files = glob($sessionDir . 'sess_*');
    $deletedSessions = 0;
    foreach ($files as $file) {
        if (filemtime($file) < strtotime('-30 days')) {
            unlink($file);
            $deletedSessions++;
        }
    }
    echo "✓ Deleted $deletedSessions old session files\n";
}

// 5. Backup database (if SQLite)
if ($_ENV['DB_CONNECTION'] === 'sqlite') {
    $backupDir = __DIR__ . '/../storage/backups/';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
    }

    $dbPath = __DIR__ . '/../' . $_ENV['DB_PATH'];
    $backupPath = $backupDir . 'backup_' . date('Y-m-d') . '.sqlite';

    if (copy($dbPath, $backupPath)) {
        echo "✓ Database backed up to $backupPath\n";

        // Keep only last 7 days of backups
        $backups = glob($backupDir . 'backup_*.sqlite');
        if (count($backups) > 7) {
            usort($backups, function($a, $b) {
                return filemtime($a) - filemtime($b);
            });

            $toDelete = array_slice($backups, 0, count($backups) - 7);
            foreach ($toDelete as $old) {
                unlink($old);
            }
            echo "✓ Cleaned old backups (kept last 7)\n";
        }
    }
}

echo "[" . date('Y-m-d H:i:s') . "] Cleanup completed!\n";
