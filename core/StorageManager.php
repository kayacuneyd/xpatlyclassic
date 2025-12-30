<?php

namespace Core;

/**
 * Hybrid Storage Manager for Xpatly
 * Uploads to Cloudflare R2 if configured, otherwise uses local storage
 */
class StorageManager
{
    private static ?string $driver = null;
    private static ?array $settings = null;

    /**
     * Get storage driver (r2 or local)
     */
    public static function getDriver(): string
    {
        if (self::$driver === null) {
            self::loadSettings();

            // Check if R2 is properly configured
            $r2Configured = !empty(self::$settings['r2_access_key_id'])
                && !empty(self::$settings['r2_secret_access_key'])
                && !empty(self::$settings['r2_bucket_name'])
                && !empty(self::$settings['r2_endpoint']);

            self::$driver = $r2Configured ? 'r2' : 'local';
        }

        return self::$driver;
    }

    /**
     * Load settings from database
     */
    private static function loadSettings(): void
    {
        if (self::$settings === null) {
            self::$settings = \Models\SiteSettings::getAll() ?? [];
        }
    }

    /**
     * Check if R2 is available
     */
    public static function isR2Available(): bool
    {
        return self::getDriver() === 'r2';
    }

    /**
     * Upload a file
     * 
     * @param string $localPath Path to the local file (usually tmp upload)
     * @param string $remotePath Destination path (e.g., "listings/123/image.jpg")
     * @return string URL or path to the uploaded file
     */
    public static function upload(string $localPath, string $remotePath): string
    {
        if (self::getDriver() === 'r2') {
            return self::uploadToR2($localPath, $remotePath);
        }

        return self::uploadToLocal($localPath, $remotePath);
    }

    /**
     * Upload to Cloudflare R2
     */
    private static function uploadToR2(string $localPath, string $remotePath): string
    {
        self::loadSettings();

        // Check if AWS SDK is available
        if (!class_exists('\Aws\S3\S3Client')) {
            // Fallback to local if SDK not installed
            error_log('AWS SDK not available, falling back to local storage');
            return self::uploadToLocal($localPath, $remotePath);
        }

        try {
            $s3Client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => self::$settings['r2_endpoint'],
                'credentials' => [
                    'key' => self::$settings['r2_access_key_id'],
                    'secret' => self::$settings['r2_secret_access_key'],
                ],
                'use_path_style_endpoint' => true,
            ]);

            $result = $s3Client->putObject([
                'Bucket' => self::$settings['r2_bucket_name'],
                'Key' => $remotePath,
                'SourceFile' => $localPath,
                'ContentType' => mime_content_type($localPath),
            ]);

            // Return public URL
            $publicUrl = rtrim(self::$settings['r2_public_url'] ?? '', '/');
            return $publicUrl . '/' . $remotePath;

        } catch (\Exception $e) {
            error_log('R2 upload failed: ' . $e->getMessage());
            // Fallback to local storage
            return self::uploadToLocal($localPath, $remotePath);
        }
    }

    /**
     * Upload to local storage
     */
    private static function uploadToLocal(string $localPath, string $remotePath): string
    {
        $uploadDir = __DIR__ . '/../public/uploads/';
        $targetPath = $uploadDir . $remotePath;

        // Create directory if it doesn't exist
        $dir = dirname($targetPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Copy the file
        if (is_uploaded_file($localPath)) {
            move_uploaded_file($localPath, $targetPath);
        } else {
            copy($localPath, $targetPath);
        }

        return '/uploads/' . $remotePath;
    }

    /**
     * Delete a file
     */
    public static function delete(string $remotePath): bool
    {
        if (self::getDriver() === 'r2') {
            return self::deleteFromR2($remotePath);
        }

        return self::deleteFromLocal($remotePath);
    }

    /**
     * Delete from R2
     */
    private static function deleteFromR2(string $remotePath): bool
    {
        self::loadSettings();

        if (!class_exists('\Aws\S3\S3Client')) {
            return self::deleteFromLocal($remotePath);
        }

        try {
            $s3Client = new \Aws\S3\S3Client([
                'version' => 'latest',
                'region' => 'auto',
                'endpoint' => self::$settings['r2_endpoint'],
                'credentials' => [
                    'key' => self::$settings['r2_access_key_id'],
                    'secret' => self::$settings['r2_secret_access_key'],
                ],
                'use_path_style_endpoint' => true,
            ]);

            $s3Client->deleteObject([
                'Bucket' => self::$settings['r2_bucket_name'],
                'Key' => $remotePath,
            ]);

            return true;

        } catch (\Exception $e) {
            error_log('R2 delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete from local storage
     */
    private static function deleteFromLocal(string $remotePath): bool
    {
        // Handle both full paths and relative paths
        if (strpos($remotePath, '/uploads/') === 0) {
            $remotePath = substr($remotePath, 9); // Remove '/uploads/'
        }

        $filePath = __DIR__ . '/../public/uploads/' . $remotePath;

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return false;
    }

    /**
     * Get full URL for a file
     */
    public static function getUrl(string $path): string
    {
        // If it's already a full URL (R2), return as-is
        if (strpos($path, 'http') === 0) {
            return $path;
        }

        // If using R2 and path is relative, construct full URL
        if (self::getDriver() === 'r2' && strpos($path, '/uploads/') !== 0) {
            self::loadSettings();
            $publicUrl = rtrim(self::$settings['r2_public_url'] ?? '', '/');
            return $publicUrl . '/' . $path;
        }

        // Local path - return as-is
        return $path;
    }

    /**
     * Reset cached driver (useful for testing or after settings change)
     */
    public static function reset(): void
    {
        self::$driver = null;
        self::$settings = null;
    }
}
