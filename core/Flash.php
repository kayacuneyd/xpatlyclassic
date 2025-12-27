<?php

namespace Core;

class Flash
{
    private const KEY = '_flash_messages';

    public static function set(string $type, string $message): void
    {
        if (!isset($_SESSION[self::KEY])) {
            $_SESSION[self::KEY] = [];
        }

        $_SESSION[self::KEY][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    public static function success(string $message): void
    {
        self::set('success', $message);
    }

    public static function error(string $message): void
    {
        self::set('error', $message);
    }

    public static function warning(string $message): void
    {
        self::set('warning', $message);
    }

    public static function info(string $message): void
    {
        self::set('info', $message);
    }

    public static function get(): array
    {
        $messages = $_SESSION[self::KEY] ?? [];
        unset($_SESSION[self::KEY]);
        return $messages;
    }

    public static function has(): bool
    {
        return !empty($_SESSION[self::KEY]);
    }
}
