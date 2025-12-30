<?php
/**
 * Migration: Add missing listing fields for advanced filtering
 * Adds: floor, year_built, energy_class columns to listings table
 */

require_once __DIR__ . '/../core/Database.php';

use Core\Database;

$db = new Database();
$conn = $db->getPDO();

// Add floor column (which floor the property is on)
$conn->exec("ALTER TABLE listings ADD COLUMN floor INTEGER");

// Add year_built column (construction year)
$conn->exec("ALTER TABLE listings ADD COLUMN year_built INTEGER");

// Add energy_class column (energy efficiency rating: A, B, C, D, E)
$conn->exec("ALTER TABLE listings ADD COLUMN energy_class VARCHAR(5)");

echo "Added missing listing fields successfully (floor, year_built, energy_class).\n";
