<?php

namespace Core;

class Uploader
{
    private const ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png'];
    private const MAX_FILE_SIZE = 5242880; // 5MB
    private const MAX_WIDTH = 1200;
    private const THUMBNAIL_WIDTH = 300;
    private const THUMBNAIL_HEIGHT = 200;
    private const JPEG_QUALITY = 85;

    private array $errors = [];

    public function upload(array $file, string $directory, string $prefix = ''): ?array
    {
        $this->errors = [];

        // Validate file
        if (!$this->validate($file)) {
            return null;
        }

        // Create directory if it doesn't exist
        $uploadPath = __DIR__ . '/../' . rtrim($directory, '/');
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        // Generate unique filename
        $extension = $this->getExtension($file['name']);
        $filename = $prefix . time() . '_' . bin2hex(random_bytes(8)) . '.' . $extension;
        $filepath = $uploadPath . '/' . $filename;

        // Process and save image
        if (!$this->processImage($file['tmp_name'], $filepath)) {
            return null;
        }

        // Generate thumbnail
        $thumbnailFilename = $prefix . time() . '_' . bin2hex(random_bytes(8)) . '_thumb.' . $extension;
        $thumbnailPath = $uploadPath . '/' . $thumbnailFilename;
        $this->createThumbnail($filepath, $thumbnailPath);

        return [
            'filename' => $filename,
            'thumbnail' => $thumbnailFilename,
            'original_name' => $file['name'],
            'size' => filesize($filepath),
            'path' => $directory . '/' . $filename
        ];
    }

    /**
     * Upload a single file using StorageManager for hybrid R2/local storage
     */
    public function uploadWithStorage(array $file, string $remotePath, string $prefix = ''): ?array
    {
        $this->errors = [];

        // Validate file
        if (!$this->validate($file)) {
            return null;
        }

        // Create temporary directory for processing
        $tempDir = sys_get_temp_dir() . '/xpatly_uploads';
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        // Generate unique filename
        $extension = $this->getExtension($file['name']);
        $filename = $prefix . time() . '_' . bin2hex(random_bytes(8)) . '.jpg'; // Always save as JPEG
        $tempFilePath = $tempDir . '/' . $filename;

        // Process and save image to temp
        if (!$this->processImage($file['tmp_name'], $tempFilePath)) {
            return null;
        }

        // Generate thumbnail
        $thumbnailFilename = $prefix . time() . '_' . bin2hex(random_bytes(8)) . '_thumb.jpg';
        $tempThumbPath = $tempDir . '/' . $thumbnailFilename;
        $this->createThumbnail($tempFilePath, $tempThumbPath);

        // Upload via StorageManager (R2 or local)
        $finalPath = StorageManager::upload($tempFilePath, $remotePath . '/' . $filename);
        $thumbPath = StorageManager::upload($tempThumbPath, $remotePath . '/' . $thumbnailFilename);

        // Clean up temp files
        @unlink($tempFilePath);
        @unlink($tempThumbPath);

        return [
            'filename' => $filename,
            'thumbnail' => $thumbnailFilename,
            'original_name' => $file['name'],
            'size' => filesize($tempFilePath) ?: 0,
            'path' => $finalPath,
            'thumb_path' => $thumbPath
        ];
    }

    /**
     * Upload multiple files using StorageManager
     */
    public function uploadMultipleWithStorage(array $files, string $remotePath, string $prefix = '', int $maxFiles = 40): array
    {
        $uploaded = [];

        // Normalize files array
        $normalizedFiles = $this->normalizeFilesArray($files);

        $count = 0;
        foreach ($normalizedFiles as $file) {
            if ($count >= $maxFiles) {
                break;
            }

            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $result = $this->uploadWithStorage($file, $remotePath, $prefix);
            if ($result) {
                $uploaded[] = $result;
                $count++;
            }
        }

        return $uploaded;
    }

    public function uploadMultiple(array $files, string $directory, string $prefix = '', int $maxFiles = 40): array
    {
        $uploaded = [];

        // Normalize files array
        $normalizedFiles = $this->normalizeFilesArray($files);

        $count = 0;
        foreach ($normalizedFiles as $file) {
            if ($count >= $maxFiles) {
                break;
            }

            if ($file['error'] === UPLOAD_ERR_NO_FILE) {
                continue;
            }

            $result = $this->upload($file, $directory, $prefix);
            if ($result) {
                $uploaded[] = $result;
                $count++;
            }
        }

        return $uploaded;
    }

    private function validate(array $file): bool
    {
        // Check for upload errors
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'File upload error: ' . $this->getUploadErrorMessage($file['error']);
            return false;
        }

        // Check file size
        if ($file['size'] > self::MAX_FILE_SIZE) {
            $this->errors[] = 'File size exceeds 5MB limit';
            return false;
        }

        // Check file extension
        $extension = $this->getExtension($file['name']);
        if (!in_array($extension, self::ALLOWED_EXTENSIONS)) {
            $this->errors[] = 'Only JPG and PNG files are allowed';
            return false;
        }

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, self::ALLOWED_MIME_TYPES)) {
            $this->errors[] = 'Invalid file type';
            return false;
        }

        // Verify it's actually an image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            $this->errors[] = 'File is not a valid image';
            return false;
        }

        return true;
    }

    private function processImage(string $source, string $destination): bool
    {
        $imageInfo = getimagesize($source);
        if ($imageInfo === false) {
            $this->errors[] = 'Failed to read image';
            return false;
        }

        [$width, $height, $type] = $imageInfo;

        // Load image based on type
        $image = match ($type) {
            IMAGETYPE_JPEG => imagecreatefromjpeg($source),
            IMAGETYPE_PNG => imagecreatefrompng($source),
            default => false
        };

        if ($image === false) {
            $this->errors[] = 'Failed to create image resource';
            return false;
        }

        // Resize if necessary
        if ($width > self::MAX_WIDTH) {
            $newWidth = self::MAX_WIDTH;
            $newHeight = (int) ($height * (self::MAX_WIDTH / $width));

            $resized = imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($type === IMAGETYPE_PNG) {
                imagealphablending($resized, false);
                imagesavealpha($resized, true);
            }

            imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
            imagedestroy($image);
            $image = $resized;
        }

        // Save as JPEG
        $result = imagejpeg($image, $destination, self::JPEG_QUALITY);
        imagedestroy($image);

        if (!$result) {
            $this->errors[] = 'Failed to save image';
            return false;
        }

        return true;
    }

    private function createThumbnail(string $source, string $destination): bool
    {
        $imageInfo = getimagesize($source);
        if ($imageInfo === false) {
            return false;
        }

        [$width, $height] = $imageInfo;

        // Calculate crop dimensions to maintain aspect ratio
        $sourceAspect = $width / $height;
        $thumbAspect = self::THUMBNAIL_WIDTH / self::THUMBNAIL_HEIGHT;

        if ($sourceAspect > $thumbAspect) {
            // Source is wider
            $newWidth = (int) ($height * $thumbAspect);
            $newHeight = $height;
            $srcX = (int) (($width - $newWidth) / 2);
            $srcY = 0;
        } else {
            // Source is taller
            $newWidth = $width;
            $newHeight = (int) ($width / $thumbAspect);
            $srcX = 0;
            $srcY = (int) (($height - $newHeight) / 2);
        }

        $image = imagecreatefromjpeg($source);
        $thumbnail = imagecreatetruecolor(self::THUMBNAIL_WIDTH, self::THUMBNAIL_HEIGHT);

        imagecopyresampled(
            $thumbnail,
            $image,
            0,
            0,
            $srcX,
            $srcY,
            self::THUMBNAIL_WIDTH,
            self::THUMBNAIL_HEIGHT,
            $newWidth,
            $newHeight
        );

        $result = imagejpeg($thumbnail, $destination, self::JPEG_QUALITY);

        imagedestroy($image);
        imagedestroy($thumbnail);

        return $result;
    }

    private function normalizeFilesArray(array $files): array
    {
        $normalized = [];

        if (isset($files['name']) && is_array($files['name'])) {
            // Multiple files
            foreach ($files['name'] as $key => $name) {
                $normalized[] = [
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ];
            }
        } else {
            // Single file
            $normalized[] = $files;
        }

        return $normalized;
    }

    private function getExtension(string $filename): string
    {
        return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    }

    private function getUploadErrorMessage(int $errorCode): string
    {
        return match ($errorCode) {
            UPLOAD_ERR_INI_SIZE => 'File exceeds server limit',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form limit',
            UPLOAD_ERR_PARTIAL => 'File was partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file',
            UPLOAD_ERR_EXTENSION => 'Upload stopped by extension',
            default => 'Unknown upload error'
        };
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function deleteFile(string $filepath): bool
    {
        $fullPath = __DIR__ . '/../' . $filepath;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }
}
