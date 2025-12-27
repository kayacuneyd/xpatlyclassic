<?php
$useMap = true; // This tells header.php to load Leaflet.js
require __DIR__ . '/../layouts/header.php';
?>

<div x-data="{ view: 'list' }" class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with View Toggle -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?= __('search.title') ?></h1>

            <!-- View Toggle Buttons -->
            <div class="flex bg-white rounded-lg shadow-sm p-1">
                <button @click="view = 'list'"
                        :class="view === 'list' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-md transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <span>List</span>
                </button>
                <button id="mapToggleBtn"
                        @click="view = 'map'"
                        :class="view === 'map' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-4 py-2 rounded-md transition-colors flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    <span>Map</span>
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4"><?= __('search.filters') ?></h2>

                    <form method="GET" action="<?= url('listings') ?>" class="space-y-4">
                        <!-- Region -->
                        <div>
                            <label class="label"><?= __('search.region') ?></label>
                            <select name="region" class="input" onchange="this.form.submit()">
                                <option value="">All Regions</option>
                                <option value="Tallinn" <?= ($filters['region'] ?? '') === 'Tallinn' ? 'selected' : '' ?>>Tallinn</option>
                                <option value="Tartu" <?= ($filters['region'] ?? '') === 'Tartu' ? 'selected' : '' ?>>Tartu</option>
                            </select>
                        </div>

                        <!-- Settlement -->
                        <?php if (!empty($filters['region'])): ?>
                        <div>
                            <label class="label"><?= __('search.settlement') ?></label>
                            <select name="settlement" class="input">
                                <option value="">All Settlements</option>
                                <!-- Dynamically populate based on region -->
                            </select>
                        </div>
                        <?php endif; ?>

                        <!-- Category -->
                        <div>
                            <label class="label"><?= __('search.category') ?></label>
                            <select name="category" class="input">
                                <option value="">All Types</option>
                                <option value="apartment" <?= ($filters['category'] ?? '') === 'apartment' ? 'selected' : '' ?>>Apartment</option>
                                <option value="house" <?= ($filters['category'] ?? '') === 'house' ? 'selected' : '' ?>>House</option>
                                <option value="room" <?= ($filters['category'] ?? '') === 'room' ? 'selected' : '' ?>>Room</option>
                            </select>
                        </div>

                        <!-- Deal Type -->
                        <div>
                            <label class="label"><?= __('search.deal_type') ?></label>
                            <select name="deal_type" class="input">
                                <option value="">All</option>
                                <option value="rent" <?= ($filters['deal_type'] ?? '') === 'rent' ? 'selected' : '' ?>>Rent</option>
                                <option value="sell" <?= ($filters['deal_type'] ?? '') === 'sell' ? 'selected' : '' ?>>Sell</option>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="label"><?= __('search.price_range') ?></label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="price_min" placeholder="Min €" class="input"
                                       value="<?= $filters['price_min'] ?? '' ?>">
                                <input type="number" name="price_max" placeholder="Max €" class="input"
                                       value="<?= $filters['price_max'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Rooms -->
                        <div>
                            <label class="label"><?= __('search.rooms') ?></label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="rooms_min" placeholder="Min" class="input"
                                       value="<?= $filters['rooms_min'] ?? '' ?>">
                                <input type="number" name="rooms_max" placeholder="Max" class="input"
                                       value="<?= $filters['rooms_max'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Area -->
                        <div>
                            <label class="label"><?= __('search.area_range') ?> (m²)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="area_min" placeholder="Min" class="input"
                                       value="<?= $filters['area_min'] ?? '' ?>">
                                <input type="number" name="area_max" placeholder="Max 500" class="input" max="500"
                                       value="<?= $filters['area_max'] ?? '' ?>">
                            </div>
                        </div>

                        <!-- Condition -->
                        <div>
                            <label class="label"><?= __('search.condition') ?></label>
                            <select name="condition" class="input">
                                <option value="">All</option>
                                <option value="new_development" <?= ($filters['condition'] ?? '') === 'new_development' ? 'selected' : '' ?>>New Development</option>
                                <option value="good" <?= ($filters['condition'] ?? '') === 'good' ? 'selected' : '' ?>>Good Condition</option>
                                <option value="renovated" <?= ($filters['condition'] ?? '') === 'renovated' ? 'selected' : '' ?>>Renovated</option>
                                <option value="needs_renovation" <?= ($filters['condition'] ?? '') === 'needs_renovation' ? 'selected' : '' ?>>Needs Renovation</option>
                            </select>
                        </div>

                        <!-- Checkboxes -->
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="expat_friendly" value="1"
                                       <?= !empty($filters['expat_friendly']) ? 'checked' : '' ?>
                                       class="mr-2">
                                <span class="text-sm"><?= __('search.expat_friendly_only') ?></span>
                            </label>

                            <label class="flex items-center">
                                <input type="checkbox" name="pets_allowed" value="1"
                                       <?= !empty($filters['pets_allowed']) ? 'checked' : '' ?>
                                       class="mr-2">
                                <span class="text-sm"><?= __('search.pets_allowed') ?></span>
                            </label>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-2">
                            <button type="submit" class="w-full btn btn-primary">
                                Apply Filters
                            </button>
                            <a href="<?= url('listings') ?>" class="w-full btn btn-secondary block text-center">
                                Clear All
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="lg:col-span-3">
                <!-- List View -->
                <div x-show="view === 'list'" x-cloak>
                    <!-- Header with Sort -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-gray-600">
                            <?= __('search.results_count', ['count' => $total]) ?>
                        </div>

                        <div>
                            <label class="text-sm text-gray-600 mr-2"><?= __('search.sort_by') ?>:</label>
                            <select name="sort" onchange="window.location.href='?<?= http_build_query(array_merge($_GET, ['sort' => ''])) ?>&sort=' + this.value"
                                    class="border rounded px-3 py-1">
                                <option value="newest" <?= ($filters['sort'] ?? 'newest') === 'newest' ? 'selected' : '' ?>>Newest</option>
                                <option value="price_low" <?= ($filters['sort'] ?? '') === 'price_low' ? 'selected' : '' ?>>Price: Low to High</option>
                                <option value="price_high" <?= ($filters['sort'] ?? '') === 'price_high' ? 'selected' : '' ?>>Price: High to Low</option>
                                <option value="area" <?= ($filters['sort'] ?? '') === 'area' ? 'selected' : '' ?>>Area: Largest</option>
                                <option value="price_per_sqm" <?= ($filters['sort'] ?? '') === 'price_per_sqm' ? 'selected' : '' ?>>Price per m²</option>
                                <option value="relevance" <?= ($filters['sort'] ?? '') === 'relevance' ? 'selected' : '' ?>>Relevance</option>
                            </select>
                        </div>
                    </div>

                    <!-- Listings Grid -->
                    <?php if (empty($listings)): ?>
                        <div class="text-center py-12">
                            <p class="text-gray-500 text-lg"><?= __('search.no_results_message') ?></p>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <?php foreach ($listings as $listing): ?>
                                <a href="<?= url("listings/{$listing['id']}") ?>" class="card group">
                                    <!-- Image -->
                                    <div class="relative h-48 bg-gray-200">
                                        <?php if (!empty($listing['primary_image'])): ?>
                                            <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $listing['primary_image'] ?>"
                                                 alt="<?= htmlspecialchars($listing['title']) ?>"
                                                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <?php endif; ?>

                                        <!-- Expat Badge -->
                                        <?php if ($listing['expat_friendly']): ?>
                                            <span class="absolute top-2 right-2 badge-expat">
                                                ✓ Expat-Friendly
                                            </span>
                                        <?php endif; ?>

                                        <!-- Deal Type Badge -->
                                        <span class="absolute top-2 left-2 px-2 py-1 bg-primary-600 text-white text-xs font-medium rounded">
                                            <?= ucfirst($listing['deal_type']) ?>
                                        </span>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-4">
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-primary-600">
                                            <?= htmlspecialchars($listing['title']) ?>
                                        </h3>

                                        <p class="text-gray-600 text-sm mb-3">
                                            📍 <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                                        </p>

                                        <div class="flex justify-between items-center">
                                            <div class="text-2xl font-bold text-primary-600">
                                                €<?= number_format($listing['price'], 0) ?>
                                                <span class="text-sm text-gray-500">/mo</span>
                                            </div>
                                            <div class="text-sm text-gray-600">
                                                <?= $listing['rooms'] ?> rooms • <?= number_format($listing['area_sqm'], 0) ?> m²
                                            </div>
                                        </div>

                                        <!-- Condition -->
                                        <div class="mt-2 text-xs text-gray-500">
                                            <?= str_replace('_', ' ', ucfirst($listing['condition'])) ?>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div class="flex justify-center mt-8 gap-2">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                       class="px-4 py-2 rounded <?= $page == $i ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-100' ?>">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Map View -->
                <div x-show="view === 'map'" x-cloak>
                    <div class="bg-white rounded-lg shadow-lg p-4 mb-4 flex justify-between items-center">
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold"><?= $total ?></span> listings found.
                            Click on markers to see details.
                        </p>
                        <button id="searchAreaBtn"
                                class="btn btn-primary flex items-center space-x-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span>Search this area</span>
                        </button>
                    </div>

                    <div class="relative">
                        <div id="map"
                             class="w-full rounded-lg shadow-lg bg-gray-100"
                             style="height: calc(100vh - 220px); min-height: 420px;"></div>

                        <!-- Loading overlay -->
                        <div id="mapLoading" style="display: none;" class="absolute inset-0 bg-white bg-opacity-75 rounded-lg">
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center">
                                    <svg class="animate-spin h-10 w-10 text-blue-600 mx-auto mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <p class="text-sm text-gray-600">Searching area...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const listings = <?= json_encode(array_map(function($listing) {
        return [
            'id' => $listing['id'],
            'title' => $listing['title'],
            'price' => $listing['price'],
            'rooms' => $listing['rooms'],
            'area' => $listing['area_sqm'],
            'settlement' => $listing['settlement'],
            'region' => $listing['region'],
            'deal_type' => $listing['deal_type'],
            'expat_friendly' => $listing['expat_friendly'],
            'lat' => $listing['latitude'] ?? null,
            'lng' => $listing['longitude'] ?? null,
            'image' => $listing['primary_image'] ?? null
        ];
    }, $listings ?? [])) ?>;

    const mapToggleBtn = document.getElementById('mapToggleBtn');
    const searchAreaBtn = document.getElementById('searchAreaBtn');
    const mapLoading = document.getElementById('mapLoading');
    let mapInitialized = false;
    let map;
    const markers = [];

    const expatIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    const regularIcon = L.icon({
        iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
        shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
        iconSize: [25, 41],
        iconAnchor: [12, 41],
        popupAnchor: [1, -34],
        shadowSize: [41, 41]
    });

    function buildPopupContent(listing) {
        return `
            <div class="p-2" style="min-width: 200px;">
                ${listing.image ? `
                    <img src="/uploads/listings/${listing.id}/${listing.image}"
                         alt="${listing.title}"
                         class="w-full h-32 object-cover rounded mb-2">
                ` : ''}
                <h3 class="font-semibold text-gray-900 mb-1">${listing.title}</h3>
                <p class="text-sm text-gray-600 mb-2">
                    📍 ${listing.settlement}, ${listing.region}
                </p>
                ${listing.expat_friendly ? '<p class="text-xs text-green-600 font-semibold mb-2">✓ Expat-Friendly</p>' : ''}
                <p class="text-lg font-bold text-blue-600 mb-2">
                    €${Number(listing.price).toLocaleString()}
                    <span class="text-xs text-gray-500">/${listing.deal_type === 'rent' ? 'mo' : 'total'}</span>
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    ${listing.rooms} rooms • ${listing.area}m²
                </p>
                <a href="<?= url('listings') ?>/${listing.id}"
                   class="block w-full text-center bg-blue-600 text-white px-3 py-2 rounded hover:bg-blue-700 text-sm">
                    View Details →
                </a>
            </div>
        `;
    }

    function placeMarkers() {
        listings.forEach(listing => {
            if (listing.lat && listing.lng) {
                const icon = listing.expat_friendly ? expatIcon : regularIcon;
                const marker = L.marker([listing.lat, listing.lng], { icon: icon }).addTo(map);
                marker.bindPopup(buildPopupContent(listing), {
                    maxWidth: 300,
                    className: 'custom-popup'
                });
                markers.push(marker);
            }
        });

        if (markers.length > 0) {
            const group = L.featureGroup(markers);
            map.fitBounds(group.getBounds().pad(0.1));
        } else {
            map.setView([59.437, 24.7536], 11);
        }
    }

    function attachMapEvents() {
        if (searchAreaBtn) {
            searchAreaBtn.addEventListener('click', function() {
                if (!map) {
                    return;
                }

                const bounds = map.getBounds();
                const ne = bounds.getNorthEast();
                const sw = bounds.getSouthWest();

                const currentParams = new URLSearchParams(window.location.search);
                currentParams.set('lat_min', sw.lat);
                currentParams.set('lat_max', ne.lat);
                currentParams.set('lng_min', sw.lng);
                currentParams.set('lng_max', ne.lng);
                currentParams.set('view', 'map');
                currentParams.delete('page');

                if (mapLoading) {
                    mapLoading.style.display = 'block';
                }

                window.location.href = '<?= url('listings') ?>?' + currentParams.toString();
            });
        }

        let moveTimeout;
        map.on('moveend', function() {
            clearTimeout(moveTimeout);
            moveTimeout = setTimeout(function() {
                if (!searchAreaBtn) return;
                searchAreaBtn.classList.add('animate-pulse');
                setTimeout(() => searchAreaBtn.classList.remove('animate-pulse'), 1200);
            }, 300);
        });
    }

    function initMap() {
        if (mapInitialized) {
            setTimeout(() => map.invalidateSize(), 150);
            return;
        }

        mapInitialized = true;
        map = L.map('map').setView([59.437, 24.7536], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        placeMarkers();
        attachMapEvents();
        setTimeout(() => map.invalidateSize(), 150);
    }

    if (mapToggleBtn) {
        mapToggleBtn.addEventListener('click', () => {
            initMap();
        });
    }

    const params = new URLSearchParams(window.location.search);
    if (params.get('view') === 'map') {
        initMap();
        const root = document.querySelector('[x-data]');
        if (root && root.__x) {
            root.__x.$data.view = 'map';
        }
    }
});
</script>

<style>
/* Custom popup styling */
.custom-popup .leaflet-popup-content-wrapper {
    padding: 0;
    border-radius: 8px;
}

.custom-popup .leaflet-popup-content {
    margin: 0;
    border-radius: 8px;
}

/* Alpine.js cloak */
[x-cloak] {
    display: none !important;
}
</style>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
