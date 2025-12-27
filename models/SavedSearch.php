<?php

namespace Models;

class SavedSearch extends Model
{
    protected static string $table = 'saved_searches';

    public static function getByEmail(string $email): array
    {
        return self::where(['user_email' => $email]);
    }

    public static function getActive(string $frequency): array
    {
        return self::where(['alert_frequency' => $frequency, 'is_active' => 1]);
    }

    public static function saveSearch(string $email, array $searchParams, string $frequency = 'daily'): int|string
    {
        return self::create([
            'user_email' => $email,
            'search_params' => json_encode($searchParams),
            'alert_frequency' => $frequency,
            'is_active' => 1
        ]);
    }

    public static function toggleActive(int $id): int
    {
        $search = self::find($id);
        $newStatus = $search['is_active'] ? 0 : 1;

        return self::update($id, ['is_active' => $newStatus]);
    }

    public static function updateLastSent(int $id): int
    {
        return self::update($id, ['last_sent' => date('Y-m-d H:i:s')]);
    }

    public static function getMatchingListings(array $searchParams, string $since): array
    {
        // Decode search params if it's a JSON string
        if (is_string($searchParams)) {
            $searchParams = json_decode($searchParams, true);
        }

        // Use the Listing model's filter method
        $searchParams['created_since'] = $since;

        return Listing::getActive($searchParams, 1, 100);
    }
}
