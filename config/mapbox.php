<?php

return [
    'api_key' => $_ENV['MAPBOX_API_KEY'] ?? '',
    'style' => 'mapbox://styles/mapbox/streets-v11',
    'default_center' => [
        'tallinn' => [
            'lng' => 24.7536,
            'lat' => 59.437
        ],
        'tartu' => [
            'lng' => 26.729,
            'lat' => 58.378
        ]
    ],
    'default_zoom' => 12
];
