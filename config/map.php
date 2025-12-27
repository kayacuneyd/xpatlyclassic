<?php

// Leaflet.js configuration (free, no API key needed)
return [
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
    'default_zoom' => 12,
    'tile_layer' => 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
    'attribution' => '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
];
