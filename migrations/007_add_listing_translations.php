<?php

require __DIR__ . '/../vendor/autoload.php';

use Core\Database;

// Add translation columns for listings
$db = new Database();

// SQLite and MySQL compatible ALTER statements
$db->query("ALTER TABLE listings ADD COLUMN title_en VARCHAR(200)");
$db->query("ALTER TABLE listings ADD COLUMN title_et VARCHAR(200)");
$db->query("ALTER TABLE listings ADD COLUMN title_ru VARCHAR(200)");
$db->query("ALTER TABLE listings ADD COLUMN description_en TEXT");
$db->query("ALTER TABLE listings ADD COLUMN description_et TEXT");
$db->query("ALTER TABLE listings ADD COLUMN description_ru TEXT");
