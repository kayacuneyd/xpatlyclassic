<?php

/**
 * Seed Database with Test Data
 * Creates users, listings, and other test data
 */

require __DIR__ . '/../vendor/autoload.php';

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

use Core\Database;

echo "[" . date('Y-m-d H:i:s') . "] Starting database seeding...\n\n";

$db = new Database();

// 1. Check if data already seeded
$result = $db->query("SELECT COUNT(*) as count FROM users WHERE email = 'owner.rent@xpatly.com'");
$row = $result->fetch();
if ($row['count'] > 0) {
    echo "⚠ Data already seeded. Exiting...\n";
    exit(0);
}

// 2. Create test users
echo "1. Creating test users...\n";

$testUsers = [
    [
        'email' => 'owner.rent@xpatly.com',
        'password_hash' => password_hash('Test123456!', PASSWORD_BCRYPT, ['cost' => 12]),
        'full_name' => 'Marja Kask',
        'phone' => '+372 5123 4567',
        'role' => 'owner',
        'locale' => 'et',
        'email_verified' => 1,
        'phone_verified' => 1,
        'description' => 'Property owner renting apartments in Tallinn'
    ],
    [
        'email' => 'owner.sell@xpatly.com',
        'password_hash' => password_hash('Test123456!', PASSWORD_BCRYPT, ['cost' => 12]),
        'full_name' => 'Toomas Tamm',
        'phone' => '+372 5234 5678',
        'role' => 'owner',
        'locale' => 'et',
        'email_verified' => 1,
        'phone_verified' => 1,
        'description' => 'Real estate agent selling properties in Estonia'
    ],
    [
        'email' => 'renter@xpatly.com',
        'password_hash' => password_hash('Test123456!', PASSWORD_BCRYPT, ['cost' => 12]),
        'full_name' => 'John Smith',
        'phone' => '+372 5345 6789',
        'role' => 'user',
        'locale' => 'en',
        'email_verified' => 1,
        'phone_verified' => 1,
        'description' => 'Expat looking to rent an apartment'
    ],
    [
        'email' => 'buyer@xpatly.com',
        'password_hash' => password_hash('Test123456!', PASSWORD_BCRYPT, ['cost' => 12]),
        'full_name' => 'Anna Petrova',
        'phone' => '+372 5456 7890',
        'role' => 'user',
        'locale' => 'ru',
        'email_verified' => 1,
        'phone_verified' => 1,
        'description' => 'Looking to buy property in Tallinn'
    ]
];

$userIds = [];
foreach ($testUsers as $user) {
    $sql = "INSERT INTO users (email, password_hash, full_name, phone, role, locale, email_verified, phone_verified, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))";
    $db->query($sql, [
        $user['email'],
        $user['password_hash'],
        $user['full_name'],
        $user['phone'],
        $user['role'],
        $user['locale'],
        $user['email_verified'],
        $user['phone_verified']
    ]);
    $userId = $db->lastInsertId();
    $userIds[$user['email']] = $userId;
    echo "✓ Created user: {$user['full_name']} ({$user['email']})\n";
}
echo "\n";

// 3. Create dummy listings
echo "\n2. Creating dummy listings...\n";

// Get user IDs
$rentOwnerId = $userIds['owner.rent@xpatly.com'];
$sellOwnerId = $userIds['owner.sell@xpatly.com'];

$listings = [
    // APARTMENTS FOR RENT
    [
        'user_id' => $rentOwnerId,
        'title' => 'Modern 2-Room Apartment in Kesklinn | Expat-Friendly',
        'description' => 'Beautiful modern apartment in the heart of Tallinn Old Town. Perfect for expats! Fully furnished with high-speed internet, washing machine, and all amenities. English-speaking landlord. Close to public transport, shops, and restaurants. All utilities included in rent. Pet-friendly.',
        'category' => 'apartment',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Pärnu mnt 45, Kesklinn',
        'latitude' => 59.4340,
        'longitude' => 24.7453,
        'rooms' => 2,
        'area_sqm' => 55.5,
        'price' => 850,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 1, 'parking' => 1, 'storage_room' => 0, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Cozy Studio in Kadriorg with Garden View',
        'description' => 'Charming studio apartment near Kadriorg Park. Ideal for a single professional or student. Includes kitchen, bathroom, and a separate sleeping area. Fresh renovation, wooden floors, large windows. Very quiet neighborhood. 10 minutes to city center by tram. Utilities not included.',
        'category' => 'apartment',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Narva mnt 102, Kadriorg',
        'latitude' => 59.4389,
        'longitude' => 24.7949,
        'rooms' => 1,
        'area_sqm' => 28.0,
        'price' => 550,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 0, 'parking' => 0, 'storage_room' => 1, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 0,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Spacious 3-Room Apartment in Mustamäe | Family-Friendly',
        'description' => 'Large family apartment in Mustamäe district. 3 separate bedrooms, spacious living room, modern kitchen, and bathroom. Perfect for families with children - playground nearby, schools, and kindergartens within walking distance. Good public transport connections. Parking included.',
        'category' => 'apartment',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Mustamäe tee 56',
        'latitude' => 59.4195,
        'longitude' => 24.7003,
        'rooms' => 3,
        'area_sqm' => 72.0,
        'price' => 950,
        'condition' => 'good',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 1, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],

    // HOUSES FOR RENT
    [
        'user_id' => $rentOwnerId,
        'title' => 'Charming Wooden House in Nõmme with Garden',
        'description' => 'Traditional Estonian wooden house in peaceful Nõmme district. 3 bedrooms, large living room with fireplace, modern kitchen, 2 bathrooms. Beautiful private garden with apple trees. Sauna in the basement. Perfect for nature-loving families. 15 minutes to city center by car. Quiet residential area.',
        'category' => 'house',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Männi tn 12, Nõmme',
        'latitude' => 59.3834,
        'longitude' => 24.6867,
        'rooms' => 4,
        'area_sqm' => 120.0,
        'price' => 1400,
        'condition' => 'good',
        'extras' => json_encode(['balcony' => 0, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Modern Villa in Viimsi with Sea View',
        'description' => 'Luxurious modern house in prestigious Viimsi area. Stunning sea views from every room. 4 bedrooms, 3 bathrooms, open-plan living and kitchen area. High-end appliances, underfloor heating, smart home system. Double garage, landscaped garden with BBQ area. Beach access 200m away. Perfect for executives.',
        'category' => 'house',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Viimsi',
        'latitude' => 59.5117,
        'longitude' => 24.8267,
        'rooms' => 5,
        'area_sqm' => 185.0,
        'price' => 2500,
        'condition' => 'new_development',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Renovated Country House in Harjumaa',
        'description' => 'Beautifully renovated farmhouse 30km from Tallinn. Perfect for remote workers seeking peace and nature. 3 bedrooms, large kitchen, cozy living room. Wood-burning stove, sauna building in the yard. 1 hectare of land with fruit trees and vegetable garden. Fiber optic internet available. Chickens welcome!',
        'category' => 'house',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Keila',
        'latitude' => 59.3019,
        'longitude' => 24.4158,
        'rooms' => 4,
        'area_sqm' => 95.0,
        'price' => 1100,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 0, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 0, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],

    // ROOMS FOR RENT
    [
        'user_id' => $rentOwnerId,
        'title' => 'Private Room in Shared Apartment - Kristiine',
        'description' => 'Nice room in a shared 3-room apartment. You will share kitchen and bathroom with 2 other international students. Fully furnished room with bed, desk, wardrobe. High-speed wifi, washing machine. Close to Tallinn University and public transport. Friendly and quiet housemates. Utilities included.',
        'category' => 'room',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Endla 45, Kristiine',
        'latitude' => 59.4292,
        'longitude' => 24.7192,
        'rooms' => 1,
        'area_sqm' => 15.0,
        'price' => 350,
        'condition' => 'good',
        'extras' => json_encode(['balcony' => 0, 'elevator' => 1, 'parking' => 0, 'storage_room' => 0, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 0,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Bright Room near Old Town - Students Welcome',
        'description' => 'Sunny room in the city center, perfect for students or young professionals. Shared apartment with one other person (also international). Fully equipped kitchen, modern bathroom. Walking distance to Old Town, supermarkets, cafes. Fast internet, all bills included. No smoking. Available immediately.',
        'category' => 'room',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Tatari 10, Kesklinn',
        'latitude' => 59.4307,
        'longitude' => 24.7497,
        'rooms' => 1,
        'area_sqm' => 12.0,
        'price' => 400,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 0, 'parking' => 0, 'storage_room' => 0, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 0,
        'status' => 'active'
    ],
    [
        'user_id' => $rentOwnerId,
        'title' => 'Cozy Room in Family Home - Pirita',
        'description' => 'Comfortable room in a friendly Estonian family home. You will have your own room and share common areas with the family. Great opportunity to practice Estonian language and learn about local culture! Home-cooked meals available (optional). Peaceful neighborhood near the beach. Bicycle included.',
        'category' => 'room',
        'deal_type' => 'rent',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Merivälja tee 22, Pirita',
        'latitude' => 59.4778,
        'longitude' => 24.8389,
        'rooms' => 1,
        'area_sqm' => 18.0,
        'price' => 380,
        'condition' => 'good',
        'extras' => json_encode(['balcony' => 0, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 0,
        'status' => 'active'
    ],

    // APARTMENTS FOR SALE
    [
        'user_id' => $sellOwnerId,
        'title' => 'Luxury 3-Room Apartment in City Center - New Development',
        'description' => 'Brand new luxury apartment in the heart of Tallinn. Premium finishes, floor-to-ceiling windows, smart home system. Open-plan kitchen and living area, 2 bedrooms, 2 bathrooms. Underground parking included. Building features gym, sauna, and rooftop terrace. Investment opportunity - high rental demand area.',
        'category' => 'apartment',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Viru väljak 2, Kesklinn',
        'latitude' => 59.4366,
        'longitude' => 24.7533,
        'rooms' => 3,
        'area_sqm' => 85.0,
        'price' => 285000,
        'condition' => 'new_development',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 1, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $sellOwnerId,
        'title' => 'Renovated 2-Room Apartment in Kalamaja',
        'description' => 'Stylishly renovated apartment in trendy Kalamaja district. Original wooden floors preserved, modern kitchen and bathroom. High ceilings, large windows. Walking distance to Balti Jaam, Telliskivi Creative City, seaside promenade. Perfect for young couples. Low maintenance costs. Energy rating A.',
        'category' => 'apartment',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Soo tn 15, Kalamaja',
        'latitude' => 59.4467,
        'longitude' => 24.7389,
        'rooms' => 2,
        'area_sqm' => 52.0,
        'price' => 165000,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 0, 'parking' => 0, 'storage_room' => 0, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $sellOwnerId,
        'title' => '4-Room Family Apartment in Lasnamäe with Balcony',
        'description' => 'Spacious family apartment perfect for growing families. 3 bedrooms, large living room, separate kitchen. Recent renovation of bathroom and kitchen. Elevator building, good condition. Close to schools, kindergartens, shopping centers. Excellent public transport connections. Parking space included in the price.',
        'category' => 'apartment',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Peterburi tee 71, Lasnamäe',
        'latitude' => 59.4325,
        'longitude' => 24.8267,
        'rooms' => 4,
        'area_sqm' => 78.0,
        'price' => 145000,
        'condition' => 'good',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 1, 'parking' => 1, 'storage_room' => 1, 'sauna' => 0, 'garage' => 0, 'fireplace' => 0]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],

    // HOUSES FOR SALE
    [
        'user_id' => $sellOwnerId,
        'title' => 'Executive Villa in Pirita with Private Beach Access',
        'description' => 'Stunning architect-designed villa in exclusive Pirita area. 5 bedrooms, 4 bathrooms, open-plan living spaces. Floor-to-ceiling windows overlooking the Baltic Sea. Premium materials throughout. Heated swimming pool, landscaped garden, double garage. Private gate access to beach. Perfect for executives seeking luxury lifestyle.',
        'category' => 'house',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Merivälja tee 5, Pirita',
        'latitude' => 59.4934,
        'longitude' => 24.8456,
        'rooms' => 6,
        'area_sqm' => 320.0,
        'price' => 1250000,
        'condition' => 'new_development',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 1, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $sellOwnerId,
        'title' => 'Traditional Estonian House in Nõmme - Fully Renovated',
        'description' => 'Charming renovated wooden house in sought-after Nõmme district. Combines historical character with modern comfort. 4 bedrooms, 2 bathrooms, spacious kitchen. New roof, windows, heating system. Beautiful mature garden with fruit trees and berry bushes. Garage and workshop. Quiet residential street near forest.',
        'category' => 'house',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Tallinn',
        'address' => 'Kuuse tn 18, Nõmme',
        'latitude' => 59.3756,
        'longitude' => 24.6934,
        'rooms' => 5,
        'area_sqm' => 145.0,
        'price' => 385000,
        'condition' => 'renovated',
        'extras' => json_encode(['balcony' => 0, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ],
    [
        'user_id' => $sellOwnerId,
        'title' => 'Eco-Friendly Modern House in Harjumaa Countryside',
        'description' => 'Newly built eco-house on 2-hectare plot, 25km from Tallinn. Solar panels, heat pump, rainwater collection system. Energy rating A+++. 3 bedrooms, open living space, home office. Large terrace with forest views. Perfect for nature lovers and remote workers. Fiber internet. Vegetable garden and greenhouse included.',
        'category' => 'house',
        'deal_type' => 'sell',
        'region' => 'Harju',
        'settlement' => 'Saku',
        'latitude' => 59.2986,
        'longitude' => 24.6564,
        'rooms' => 4,
        'area_sqm' => 135.0,
        'price' => 295000,
        'condition' => 'new_development',
        'extras' => json_encode(['balcony' => 1, 'elevator' => 0, 'parking' => 1, 'storage_room' => 1, 'sauna' => 1, 'garage' => 1, 'fireplace' => 1]),
        'expat_friendly' => 1,
        'pets_allowed' => 1,
        'status' => 'active'
    ]
];

foreach ($listings as $listing) {
    $pricePerSqm = $listing['price'] / $listing['area_sqm'];

    $sql = "INSERT INTO listings (
        user_id, title, description, category, deal_type, region, settlement, address,
        latitude, longitude, rooms, area_sqm, price, price_per_sqm, condition, extras,
        expat_friendly, pets_allowed, status, created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, datetime('now'), datetime('now'))";

    $db->query($sql, [
        $listing['user_id'],
        $listing['title'],
        $listing['description'],
        $listing['category'],
        $listing['deal_type'],
        $listing['region'],
        $listing['settlement'],
        $listing['address'],
        $listing['latitude'],
        $listing['longitude'],
        $listing['rooms'],
        $listing['area_sqm'],
        $listing['price'],
        $pricePerSqm,
        $listing['condition'],
        $listing['extras'],
        $listing['expat_friendly'],
        $listing['pets_allowed'],
        $listing['status']
    ]);

    $listingId = $db->lastInsertId();
    echo "✓ Created listing: {$listing['title']}\n";
}

echo "\n[" . date('Y-m-d H:i:s') . "] Seeding completed successfully!\n";
echo "\n=== Test Accounts ===\n";
echo "Super Admin: kayacuneyd@gmail.com (Password: Admin123456!)\n";
echo "Owner (Rent): owner.rent@xpatly.com (Password: Test123456!)\n";
echo "Owner (Sell): owner.sell@xpatly.com (Password: Test123456!)\n";
echo "Renter: renter@xpatly.com (Password: Test123456!)\n";
echo "Buyer: buyer@xpatly.com (Password: Test123456!)\n";
echo "\n=== Statistics ===\n";
echo "Users created: " . count($testUsers) . "\n";
echo "Listings created: " . count($listings) . "\n";
echo "  - Apartments for rent: 3\n";
echo "  - Houses for rent: 3\n";
echo "  - Rooms for rent: 3\n";
echo "  - Apartments for sale: 3\n";
echo "  - Houses for sale: 3\n";
