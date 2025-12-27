<?php

namespace Core;

class Cache
{
    private const CACHE_DIR = __DIR__ . '/../storage/cache/';
    private const DEFAULT_TTL = 900; // 15 minutes

    public static function get(string $key, mixed $default = null): mixed
    {
        $filename = self::getFilename($key);

        if (!file_exists($filename)) {
            return $default;
        }

        $data = unserialize(file_get_contents($filename));

        // Check if expired
        if ($data['expires_at'] < time()) {
            self::forget($key);
            return $default;
        }

        return $data['value'];
    }

    public static function put(string $key, mixed $value, int $ttl = self::DEFAULT_TTL): bool
    {
        $filename = self::getFilename($key);

        $data = [
            'value' => $value,
            'expires_at' => time() + $ttl
        ];

        return file_put_contents($filename, serialize($data)) !== false;
    }

    public static function remember(string $key, callable $callback, int $ttl = self::DEFAULT_TTL): mixed
    {
        $value = self::get($key);

        if ($value !== null) {
            return $value;
        }

        $value = $callback();
        self::put($key, $value, $ttl);

        return $value;
    }

    public static function forget(string $key): bool
    {
        $filename = self::getFilename($key);

        if (file_exists($filename)) {
            return unlink($filename);
        }

        return false;
    }

    public static function flush(): void
    {
        $files = glob(self::CACHE_DIR . '*.cache');
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }

    public static function has(string $key): bool
    {
        return self::get($key) !== null;
    }

    private static function getFilename(string $key): string
    {
        return self::CACHE_DIR . md5($key) . '.cache';
    }

    private static function ensureCacheDir(): void
    {
        if (!is_dir(self::CACHE_DIR)) {
            mkdir(self::CACHE_DIR, 0755, true);
        }
    }
}
