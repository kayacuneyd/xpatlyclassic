<?php

namespace Core;

class Translation
{
    private static array $translations = [];
    private static string $locale = 'en';
    private static string $fallbackLocale = 'en';

    public static function load(string $locale): void
    {
        self::$locale = $locale;

        if (isset(self::$translations[$locale])) {
            return;
        }

        $file = __DIR__ . "/../languages/{$locale}.json";

        if (!file_exists($file)) {
            $locale = self::$fallbackLocale;
            $file = __DIR__ . "/../languages/{$locale}.json";
        }

        if (file_exists($file)) {
            $content = file_get_contents($file);
            self::$translations[$locale] = json_decode($content, true);
        } else {
            self::$translations[$locale] = [];
        }
    }

    public static function get(string $key, array $replace = []): string
    {
        if (empty(self::$translations)) {
            self::load(self::$locale);
        }

        $keys = explode('.', $key);
        $value = self::$translations[self::$locale] ?? [];

        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                // Try fallback locale
                $value = self::$translations[self::$fallbackLocale] ?? [];
                foreach ($keys as $k2) {
                    if (!isset($value[$k2])) {
                        return $key;
                    }
                    $value = $value[$k2];
                }
                break;
            }
            $value = $value[$k];
        }

        if (!is_string($value)) {
            return $key;
        }

        // Replace placeholders (supports both :key and {key} syntaxes)
        foreach ($replace as $search => $replacement) {
            $value = str_replace(
                [":{$search}", '{' . $search . '}'],
                $replacement,
                $value
            );
        }

        return $value;
    }

    public static function choice(string $key, int $count, array $replace = []): string
    {
        $translation = self::get($key, $replace);

        // Simple pluralization
        if ($count === 1) {
            return $translation;
        }

        // For plural form, append _plural to key
        return self::get($key . '_plural', array_merge($replace, ['count' => $count]));
    }

    public static function setLocale(string $locale): void
    {
        self::$locale = $locale;
        self::load($locale);
    }

    public static function getLocale(): string
    {
        return self::$locale;
    }

    public static function formatDate(string|\DateTime $date, ?string $format = null): string
    {
        if (is_string($date)) {
            $date = new \DateTime($date);
        }

        if ($format === null) {
            $format = match (self::$locale) {
                'et', 'ru' => 'd.m.Y',
                default => 'm/d/Y'
            };
        }

        return $date->format($format);
    }

    public static function formatNumber(float $number, int $decimals = 0): string
    {
        $decimalSeparator = match (self::$locale) {
            'et', 'ru' => ',',
            default => '.'
        };

        $thousandsSeparator = match (self::$locale) {
            'et', 'ru' => ' ',
            default => ','
        };

        return number_format($number, $decimals, $decimalSeparator, $thousandsSeparator);
    }

    public static function formatCurrency(float $amount, string $currency = '€'): string
    {
        return $currency . self::formatNumber($amount, 2);
    }

    public static function formatArea(float $area): string
    {
        return self::formatNumber($area, 2) . ' m²';
    }
}
