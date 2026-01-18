<?php
$useMap = false; // Load Leaflet lazily
require __DIR__ . '/../layouts/header.php';
?>

<div x-data="{
    view: 'list',
    showFilters: true,
    showToast: false,
    toastMessage: '',
    toastType: 'info',

    toast(message, type = 'info') {
        this.toastMessage = message;
        this.toastType = type;
        this.showToast = true;
        setTimeout(() => { this.showToast = false; }, 3000);
    }
}" class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header with View Toggle -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-4xl font-bold text-gray-900"><?= __('search.title') ?></h1>
                <p class="text-gray-600 mt-2"><?= $total ?> <?= __('search.properties_found') ?? 'properties found' ?></p>
            </div>

            <!-- View Toggle Buttons -->
            <div class="flex bg-white rounded-xl shadow-md p-1.5 border border-gray-200">
                <button id="listToggleBtn" @click="view = 'list'"
                        :class="view === 'list' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-5 py-2.5 rounded-lg transition-all duration-200 flex items-center space-x-2 font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <span>List</span>
                </button>
                <button id="mapToggleBtn"
                        @click="view = 'map'"
                        :class="view === 'map' ? 'bg-primary-600 text-white shadow-sm' : 'text-gray-600 hover:bg-gray-100'"
                        class="px-5 py-2.5 rounded-lg transition-all duration-200 flex items-center space-x-2 font-medium">
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
                <div class="bg-white rounded-2xl shadow-2xl p-6 sticky top-20">
                    <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-secondary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                        </svg>
                        <?= __('search.filters') ?>
                    </h2>

                    <form method="GET" action="<?= url('listings') ?>" class="space-y-4" id="filterForm" onsubmit="cleanEmptyParams(event)">
                        <!-- Deal Type - FIRST as per plan -->
                        <div x-data="{ dealType: '<?= $filters['deal_type'] ?? '' ?>' }">
                            <label for="filter_deal_type" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.deal_type') ?></label>
                            <select id="filter_deal_type" name="deal_type" x-model="dealType"
                                    @change="window.dispatchEvent(new CustomEvent('deal-type-changed', { detail: { dealType: dealType } }))"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('search.all') ?? 'All' ?></option>
                                <option value="rent"><?= __('search.rent') ?? 'Rent' ?></option>
                                <option value="sell"><?= __('search.sell') ?? 'Buy' ?></option>
                            </select>
                        </div>

                        <!-- City -->
                        <div>
                            <label for="filter_city" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.city') ?? 'City' ?></label>
                            <select id="filter_city" name="city" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('search.all_cities') ?? 'All Cities' ?></option>
                                <option value="Tallinn">Tallinn</option>
                                <option value="Tartu">Tartu</option>
                            </select>
                        </div>

                        <!-- District / Subregion -->
                        <div>
                            <label for="filter_settlement" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.subregion') ?? 'District / Parish' ?></label>
                            <select id="filter_settlement" name="settlement" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors" disabled>
                                <option value=""><?= __('search.all_subregions') ?? 'All Districts' ?></option>
                            </select>
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="filter_category" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.category') ?></label>
                            <select id="filter_category" name="category" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('search.all_types') ?? 'All Types' ?></option>
                                <option value="apartment" <?= ($filters['category'] ?? '') === 'apartment' ? 'selected' : '' ?>><?= __('search.apartment') ?? 'Apartment' ?></option>
                                <option value="house" <?= ($filters['category'] ?? '') === 'house' ? 'selected' : '' ?>><?= __('search.house') ?? 'House' ?></option>
                                <option value="room" <?= ($filters['category'] ?? '') === 'room' ? 'selected' : '' ?>><?= __('search.room') ?? 'Room' ?></option>
                            </select>
                        </div>

                        <!-- Price Range with Slider -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100" x-data="{
                            priceMin: <?= $filters['price_min'] ?? 0 ?>,
                            priceMax: <?= $filters['price_max'] ?? (($filters['deal_type'] ?? '') === 'sell' ? 500000 : 5000) ?>,
                            dealType: '<?= $filters['deal_type'] ?? '' ?>',

                            get maxPriceLimit() {
                                return this.dealType === 'sell' ? 500000 : 5000;
                            },
                            get priceStep() {
                                return this.dealType === 'sell' ? 5000 : 50;
                            },
                            formatPrice(val) { return '€' + Number(val).toLocaleString(); },

                            init() {
                                // Listen for deal type changes
                                window.addEventListener('deal-type-changed', (e) => {
                                    this.dealType = e.detail.dealType;
                                    // Reset price ranges when deal type changes
                                    if (this.dealType === 'sell') {
                                        this.priceMax = 500000;
                                        this.priceMin = 0;
                                    } else if (this.dealType === 'rent') {
                                        this.priceMax = 5000;
                                        this.priceMin = 0;
                                    }
                                });
    }
}">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wide"><?= __('search.price_range') ?></label>
                                <div class="text-xs font-bold text-secondary-700">
                                    <span x-text="formatPrice(priceMin)"></span> - <span x-text="formatPrice(priceMax)"></span>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <input type="range" name="price_min" min="0" :max="maxPriceLimit" :step="priceStep"
                                       x-model="priceMin" aria-label="Minimum price"
                                       :class="dealType === 'sell' ? 'accent-orange-600' : 'accent-blue-600'"
                                       class="w-full">
                                <input type="range" name="price_max" min="0" :max="maxPriceLimit" :step="priceStep"
                                       x-model="priceMax" aria-label="Maximum price"
                                       :class="dealType === 'sell' ? 'accent-orange-600' : 'accent-blue-600'"
                                       class="w-full">
                            </div>
                            <div class="flex items-center space-x-3 mt-3">
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                                    <input type="number" name="price_min" x-model="priceMin" aria-label="Price minimum"
                                           min="0" :max="maxPriceLimit" :step="priceStep"
                                           class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center">
                                </div>
                                <span class="text-gray-400">-</span>
                                <div class="relative flex-1">
                                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                                    <input type="number" name="price_max" x-model="priceMax" aria-label="Price maximum"
                                           min="0" :max="maxPriceLimit" :step="priceStep"
                                           class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center">
                                </div>
                            </div>
                            <!-- Helper Text with Limits -->
                                <p class="text-xs mt-2 text-secondary-700">
                                <span x-show="dealType === 'rent'">Rent: Max €5,000/month</span>
                                <span x-show="dealType === 'sell'">Sale: Max €500,000</span>
                                <span x-show="!dealType || dealType === ''">Select deal type to set price limits</span>
                            </p>
                        </div>

                            <!-- Rooms with Slider -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100" x-data="{
                            roomsMin: <?= $filters['rooms_min'] ?? 1 ?>,
                            roomsMax: <?= $filters['rooms_max'] ?? 6 ?>
                        }">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wide"><?= __('search.rooms') ?></label>
                                <div class="text-xs font-bold text-secondary-700">
                                    <span x-text="roomsMin"></span> - <span x-text="roomsMax"></span> <?= __('search.rooms_label') ?? 'rooms' ?>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <input type="range" name="rooms_min" min="1" max="6" step="1"
                                       x-model="roomsMin" aria-label="Minimum rooms" class="w-full accent-primary-600">
                                <input type="range" name="rooms_max" min="1" max="6" step="1"
                                       x-model="roomsMax" aria-label="Maximum rooms" class="w-full accent-primary-600">
                            </div>
                        </div>

                        <!-- Area with Slider -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100" x-data="{
                            areaMin: <?= $filters['area_min'] ?? 20 ?>,
                            areaMax: <?= $filters['area_max'] ?? 200 ?>
                        }">
                            <div class="flex justify-between items-center mb-2">
                                <label class="text-xs font-bold text-gray-700 uppercase tracking-wide"><?= __('search.area_range') ?></label>
                                <div class="text-xs font-bold text-secondary-700">
                                    <span x-text="areaMin"></span> - <span x-text="areaMax"></span> m²
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <input type="range" name="area_min" min="10" max="300" step="10"
                                       x-model="areaMin" aria-label="Minimum area" class="w-full accent-primary-600">
                                <input type="range" name="area_max" min="10" max="300" step="10"
                                       x-model="areaMax" aria-label="Maximum area" class="w-full accent-primary-600">
                            </div>
                        </div>

                        <!-- Advanced Filters - Collapsible -->
                        <div x-data="{ advancedOpen: false }">
                            <button type="button" @click="advancedOpen = !advancedOpen"
                                    class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition-colors">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                    </svg>
                                    <?= __('search.advanced_filters') ?? 'Advanced Filters' ?>
                                </span>
                                <svg class="w-4 h-4 transition-transform duration-200" :class="advancedOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="advancedOpen" x-collapse class="space-y-4 pt-4">
                                <!-- Condition -->
                            <div>
                                <label for="filter_condition" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.condition') ?></label>
                                <select id="filter_condition" name="condition" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                    <option value=""><?= __('search.all') ?? 'All' ?></option>
                                    <option value="new_development" <?= ($filters['condition'] ?? '') === 'new_development' ? 'selected' : '' ?>><?= __('search.new_development') ?? 'New Development' ?></option>
                                    <option value="good" <?= ($filters['condition'] ?? '') === 'good' ? 'selected' : '' ?>><?= __('search.good_condition') ?? 'Good Condition' ?></option>
                                    <option value="renovated" <?= ($filters['condition'] ?? '') === 'renovated' ? 'selected' : '' ?>><?= __('search.renovated') ?? 'Renovated' ?></option>
                                    <option value="needs_renovation" <?= ($filters['condition'] ?? '') === 'needs_renovation' ? 'selected' : '' ?>><?= __('search.needs_renovation') ?? 'Needs Renovation' ?></option>
                                </select>
                            </div>

                                <!-- Floor -->
                                <div>
                                    <label for="floor_min" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.floor') ?? 'Floor' ?></label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input id="floor_min" type="number" name="floor_min" placeholder="Min" aria-label="Minimum floor"
                                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center" value="<?= $filters['floor_min'] ?? '' ?>">
                                        <input id="floor_max" type="number" name="floor_max" placeholder="Max" aria-label="Maximum floor"
                                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center" value="<?= $filters['floor_max'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- Year Built -->
                                <div>
                                    <label for="year_min" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.year_built') ?? 'Year Built' ?></label>
                                    <div class="grid grid-cols-2 gap-3">
                                        <input id="year_min" type="number" name="year_min" placeholder="From" aria-label="Year built from"
                                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center" value="<?= $filters['year_min'] ?? '' ?>">
                                        <input id="year_max" type="number" name="year_max" placeholder="To" aria-label="Year built to"
                                               class="w-full px-3 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center" value="<?= $filters['year_max'] ?? '' ?>">
                                    </div>
                                </div>

                                <!-- Energy Class -->
                                <div>
                                    <label for="filter_energy_class" class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block"><?= __('search.energy_class') ?? 'Energy Class' ?></label>
                                    <select id="filter_energy_class" name="energy_class" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                        <option value=""><?= __('search.all') ?? 'All' ?></option>
                                        <option value="A" <?= ($filters['energy_class'] ?? '') === 'A' ? 'selected' : '' ?>>A</option>
                                        <option value="B" <?= ($filters['energy_class'] ?? '') === 'B' ? 'selected' : '' ?>>B</option>
                                        <option value="C" <?= ($filters['energy_class'] ?? '') === 'C' ? 'selected' : '' ?>>C</option>
                                        <option value="D" <?= ($filters['energy_class'] ?? '') === 'D' ? 'selected' : '' ?>>D</option>
                                        <option value="E" <?= ($filters['energy_class'] ?? '') === 'E' ? 'selected' : '' ?>>E</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Extras -->
                        <div>
                            <label class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-3 block"><?= __('search.extras') ?? 'Extras' ?></label>
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <?php
                                $extras = [
                                    'elevator' => __('search.elevator') ?? 'Elevator',
                                    'balcony' => __('search.balcony') ?? 'Balcony',
                                    'sauna' => __('search.sauna') ?? 'Sauna',
                                    'garage' => __('search.garage') ?? 'Garage',
                                    'furnished' => __('search.furnished') ?? 'Furnished',
                                    'storage' => __('search.storage') ?? 'Storage',
                                    'bathtub' => __('search.bathtub') ?? 'Bathtub',
                                    'pets_allowed' => __('search.pets_allowed') ?? 'Pets OK',
                                ];
                                $selectedExtras = $filters['extras'] ?? [];
                                foreach ($extras as $value => $label): ?>
                                <label class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="extras[]" value="<?= $value ?>"
                                           <?= in_array($value, $selectedExtras) ? 'checked' : '' ?>
                                           class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                    <?= $label ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="space-y-3 pt-6 border-t border-gray-200">
                            <button type="submit" class="w-full py-4 text-base font-bold rounded-xl flex items-center justify-center gap-2 transition-all hover:shadow-lg transform active:scale-[0.98]" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: #ffffff;">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <?= __('common.search') ?? 'Search' ?>
                            </button>
                            <a href="<?= url('listings') ?>" class="w-full py-3 px-4 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors block text-center">
                                <?= __('search.clear_all') ?? 'Clear All' ?>
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Results Section -->
            <div class="lg:col-span-3">
                <!-- List View -->
                <div id="listings-results" x-show="view === 'list'" x-cloak>
                    <!-- Header with Sort -->
                    <div class="flex justify-between items-center mb-6">
                        <div class="text-gray-600" id="results-count">
                            <?= __('search.results_count', ['count' => $total]) ?>
                        </div>

                        <div>
                            <label for="sort_select" class="text-sm text-gray-600 mr-2"><?= __('search.sort_by') ?>:</label>
                            <select id="sort_select" name="sort" class="border rounded px-3 py-1">
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
                        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                            <?php foreach ($listings as $listing): ?>
                                <a href="<?= url("listings/{$listing['id']}") ?>" class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                    <!-- Image -->
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <?php if (!empty($listing['primary_image'])): ?>
                                            <?php
                                            // Use thumbnail for better performance (300x200px instead of 1200px)
                                            $thumbnailName = str_replace('.jpg', '_thumb.jpg', $listing['primary_image']);
                                            $thumbnailName = str_replace('.jpeg', '_thumb.jpg', $thumbnailName);
                                            $thumbnailName = str_replace('.png', '_thumb.jpg', $thumbnailName);
                                            $fullImage = '/uploads/listings/' . $listing['id'] . '/' . $listing['primary_image'];
                                            $placeholderImage = asset('images/listing-placeholder.svg');
                                            ?>
                                            <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $thumbnailName ?>"
                                                 data-full-src="<?= htmlspecialchars($fullImage) ?>"
                                                 data-placeholder="<?= $placeholderImage ?>"
                                                 onerror="if (!this.dataset.fallbackStage) { this.dataset.fallbackStage = 'full'; this.src = this.dataset.fullSrc; } else { this.onerror = null; this.src = this.dataset.placeholder; }"
                                                 alt="<?= htmlspecialchars($listing['title']) ?>"
                                                 width="300" height="200"
                                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                 loading="lazy">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Deal Type Badge -->
                                        <div class="absolute top-4 right-4">
                                            <span class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold text-gray-900 rounded-full shadow-sm">
                                                <?= ucfirst($listing['deal_type']) ?>
                                            </span>
                                        </div>

                                        <!-- Expat Badge -->
                                        <?php if ($listing['expat_friendly']): ?>
                                            <div class="absolute top-4 left-4">
                                                <span class="px-3 py-1 bg-green-500/90 backdrop-blur text-xs font-bold text-white rounded-full shadow-sm">
                                                    Expat-Friendly
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-lg font-bold text-gray-900 line-clamp-1 group-hover:text-secondary-700 hover:underline underline-offset-2 transition-colors">
                                                <?= htmlspecialchars($listing['title']) ?>
                                            </h3>
                                        </div>

                                        <p class="text-gray-500 text-sm mb-4 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                                        </p>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                    </svg>
                                                    <?= $listing['rooms'] ?>
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/>
                                                    </svg>
                                                    <?= (int) $listing['area_sqm'] ?> m²
                                                </span>
                                            </div>
                                            <span class="text-xl font-bold text-secondary-700">€<?= number_format($listing['price']) ?></span>
                                        </div>
                                    </div>
                                </a>
                            <?php endforeach; ?>
                        </div>

                        <!-- Pagination -->
                        <?php if ($totalPages > 1): ?>
                            <div id="pagination" class="flex justify-center mt-12 gap-2">
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <a href="?<?= http_build_query(array_merge($_GET, ['page' => $i])) ?>"
                                       data-page="<?= $i ?>"
                                       class="px-5 py-3 rounded-lg font-medium transition-all <?= $page == $i ? 'bg-primary-600 text-white shadow-md' : 'bg-white text-gray-700 hover:bg-gray-100 border border-gray-200' ?>"
                                       aria-label="Page <?= $i ?>">
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
                             class="w-full rounded-lg shadow-lg bg-gray-100 h-[calc(100vh-180px)] sm:h-[calc(100vh-220px)] min-h-[300px] sm:min-h-[420px]"></div>

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
    function loadLeaflet() {
        if (window.L) {
            return Promise.resolve();
        }
        return new Promise(function(resolve, reject) {
            const link = document.createElement('link');
            link.rel = 'stylesheet';
            link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            link.onload = function() {
                const script = document.createElement('script');
                script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            };
            link.onerror = reject;
            document.head.appendChild(link);
        });
    }
    const subregions = {
        "Tallinn": [
            "Kesklinn",
            "Kristiine",
            "Mustamäe",
            "Lasnamäe",
            "Põhja-Tallinn"
        ],
        "Tartu": [
            "Kesklinn",
            "Annelinn",
            "Karlova",
            "Supilinn",
            "Ihaste"
        ]
    };

    const citySelect = document.getElementById('filter_city');
    const settlementSelect = document.getElementById('filter_settlement');
    const currentSettlement = "<?= addslashes($filters['settlement'] ?? '') ?>";
    const currentCityParam = "<?= addslashes($_GET['city'] ?? '') ?>";

    function populateSettlements(city, selected) {
        const options = subregions[city] || [];
        settlementSelect.innerHTML = '';
        const defaultOption = document.createElement('option');
        defaultOption.value = '';
        defaultOption.textContent = '<?= addslashes(__('search.all_subregions') ?? 'All Districts') ?>';
        settlementSelect.appendChild(defaultOption);
        options.forEach(function(option) {
            const opt = document.createElement('option');
            opt.value = option;
            opt.textContent = option;
            if (selected && selected === option) {
                opt.selected = true;
            }
            settlementSelect.appendChild(opt);
        });
        settlementSelect.disabled = options.length === 0;
    }

    function inferCityFromSettlement(settlement) {
        if (!settlement) return '';
        for (const city in subregions) {
            if (subregions[city].includes(settlement)) {
                return city;
            }
        }
        return '';
    }

    if (citySelect && settlementSelect) {
        const inferredCity = currentCityParam || inferCityFromSettlement(currentSettlement);
        if (inferredCity) {
            citySelect.value = inferredCity;
            populateSettlements(inferredCity, currentSettlement);
        } else {
            populateSettlements('', '');
        }

        citySelect.addEventListener('change', function() {
            populateSettlements(citySelect.value, '');
        });
    }

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
    const listToggleBtn = document.getElementById('listToggleBtn');
    const searchAreaBtn = document.getElementById('searchAreaBtn');
    const mapLoading = document.getElementById('mapLoading');
    let mapInitialized = false;
    let map;
    const markers = [];

    // Rough distance calculator for bounds (Haversine)
    function distanceKm(lat1, lng1, lat2, lng2) {
        const toRad = (v) => v * Math.PI / 180;
        const R = 6371; // km
        const dLat = toRad(lat2 - lat1);
        const dLng = toRad(lng2 - lng1);
        const a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
            Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
            Math.sin(dLng / 2) * Math.sin(dLng / 2);
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    let expatIcon;
    let regularIcon;

    function ensureIcons() {
        if (!window.L || expatIcon || regularIcon) {
            return;
        }

        expatIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-green.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        regularIcon = L.icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-2x-blue.png',
            shadowUrl: 'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });
    }

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
                    ${listing.settlement}, ${listing.region}
                </p>
                ${listing.expat_friendly ? '<p class="text-xs text-green-600 font-semibold mb-2">Expat-Friendly</p>' : ''}
                <p class="text-lg font-bold text-blue-600 mb-2">
                    €${Number(listing.price).toLocaleString()}
                    <span class="text-xs text-gray-500">/${listing.deal_type === 'rent' ? 'mo' : 'total'}</span>
                </p>
                <p class="text-sm text-gray-600 mb-3">
                    ${listing.rooms} rooms • ${listing.area}m²
                </p>
                <a href="<?= url('listings') ?>/${listing.id}"
                   class="popup-cta block w-full text-center bg-blue-600 px-3 py-2 rounded hover:bg-blue-700 text-sm">
                    View Details →
                </a>
            </div>
        `;
    }

    function placeMarkers() {
        ensureIcons();
        if (!map || !expatIcon || !regularIcon) {
            return;
        }
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
            const bounds = group.getBounds().pad(0.05);
            const diagKm = distanceKm(
                bounds.getSouthWest().lat, bounds.getSouthWest().lng,
                bounds.getNorthEast().lat, bounds.getNorthEast().lng
            );

            // Single or tightly-clustered city view
            if (markers.length === 1 || diagKm <= 15) {
                map.setView(bounds.getCenter(), 13);
            } else if (diagKm <= 50) {
                map.setView(bounds.getCenter(), 11);
            } else {
                map.fitBounds(bounds);
            }
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
        const cityBounds = {
            "Tallinn": [[59.30, 24.45], [59.55, 25.20]],
            "Tartu": [[58.28, 26.60], [58.48, 26.92]]
        };
        // Fit to Baltics: Estonia, Latvia, Lithuania
        const balticsBounds = [
            [56.0, 20.5], // SW corner
            [60.0, 28.0]  // NE corner
        ];
        map = L.map('map');
        ensureIcons();

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(map);

        const urlParams = new URLSearchParams(window.location.search);
        const cityParam = urlParams.get('city') || '';
        const settlementParam = urlParams.get('settlement') || '';
        const inferredCity = cityParam || inferCityFromSettlement(settlementParam);
        const bounds = cityBounds[inferredCity] || balticsBounds;

        map.fitBounds(bounds, { padding: [20, 20] });

        placeMarkers();
        attachMapEvents();
        setTimeout(() => map.invalidateSize(), 150);
    }

    function setViewParam(value) {
        const params = new URLSearchParams(window.location.search);
        if (value) {
            params.set('view', value);
        } else {
            params.delete('view');
        }
        const query = params.toString();
        const newUrl = query ? `${window.location.pathname}?${query}` : window.location.pathname;
        window.history.replaceState({}, '', newUrl);
    }

    if (mapToggleBtn) {
        mapToggleBtn.addEventListener('click', () => {
            setViewParam('map');
            loadLeaflet().then(initMap).catch(() => {});
        });
    }

    if (listToggleBtn) {
        listToggleBtn.addEventListener('click', () => {
            setViewParam('');
        });
    }

    const params = new URLSearchParams(window.location.search);
    if (params.get('view') === 'map') {
        loadLeaflet().then(function() {
            initMap();
            const root = document.querySelector('[x-data]');
            if (root && root.__x) {
                root.__x.$data.view = 'map';
            }
        }).catch(() => {});
    }
});

// Clean empty parameters from form submission
function cleanEmptyParams(event) {
    const form = event.target;
    const formData = new FormData(form);
    const params = new URLSearchParams();
    const currentParams = new URLSearchParams(window.location.search);

    // Define default slider values that should be excluded from URL
    const defaults = {
        'price_min': '0',
        'rooms_min': '1',
        'rooms_max': '6',
        'area_min': '20',
        'area_max': '200',
        'floor_min': '',
        'floor_max': '',
        'year_min': '',
        'year_max': ''
    };

    for (const [key, value] of formData.entries()) {
        // Skip empty values
        if (!value || value === '') continue;

        // Skip default slider values (to keep URLs clean)
        if (defaults[key] && value === defaults[key]) continue;

        // Add all other parameters
        params.append(key, value);
    }

    const sortSelect = document.getElementById('sort_select');
    const sortValue = sortSelect?.value || currentParams.get('sort');
    if (sortValue) {
        params.set('sort', sortValue);
    }

    const viewValue = currentParams.get('view');
    if (viewValue) {
        params.set('view', viewValue);
    }

    // Prevent default form submission
    event.preventDefault();

    applyFilters(params, form.action);
}

function bindSortListener() {
    const sortSelect = document.getElementById('sort_select');
    if (!sortSelect || sortSelect.dataset.bound === '1') return;
    sortSelect.dataset.bound = '1';
    sortSelect.addEventListener('change', function() {
        const params = new URLSearchParams(window.location.search);
        params.set('sort', sortSelect.value);
        params.delete('page');
        applyFilters(params, window.location.pathname);
    });
}

function bindPaginationLinks() {
    const pagination = document.getElementById('pagination');
    if (!pagination || pagination.dataset.bound === '1') return;
    pagination.dataset.bound = '1';
    pagination.addEventListener('click', function(event) {
        const link = event.target.closest('a[data-page]');
        if (!link) return;
        event.preventDefault();
        const params = new URLSearchParams(window.location.search);
        params.set('page', link.dataset.page);
        applyFilters(params, window.location.pathname);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

function applyFilters(params, actionUrl) {
    const url = new URL(actionUrl, window.location.origin);
    params.forEach((value, key) => url.searchParams.set(key, value));

    const currentParams = new URLSearchParams(window.location.search);
    let isMapView = currentParams.get('view') === 'map';
    if (!isMapView) {
        const root = document.querySelector('[x-data]');
        if (root && root.__x && root.__x.$data && root.__x.$data.view === 'map') {
            isMapView = true;
        }
    }
    if (isMapView) {
        if (!currentParams.get('view')) {
            params.set('view', 'map');
        }
        window.location.href = url.toString();
        return;
    }

    const resultsEl = document.getElementById('listings-results');
    if (!resultsEl) {
        window.location.href = url.toString();
        return;
    }

    resultsEl.classList.add('opacity-50');

    fetch(url.toString(), {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then((response) => response.text())
        .then((html) => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const newResults = doc.getElementById('listings-results');
            if (newResults) {
                resultsEl.innerHTML = newResults.innerHTML;
            }

            resultsEl.classList.remove('opacity-50');
            window.history.pushState({}, '', url.toString());
            bindSortListener();
            bindPaginationLinks();
        })
        .catch(() => {
            window.location.href = url.toString();
        });
}

document.addEventListener('DOMContentLoaded', function() {
    bindSortListener();
    bindPaginationLinks();
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

.custom-popup .popup-cta {
    color: #ffffff;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.35);
}

.custom-popup .popup-cta:hover {
    color: #fde68a;
}

/* Alpine.js cloak */
[x-cloak] {
    display: none !important;
}
</style>

<!-- Toast Notification -->
<div x-show="showToast"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-4"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-4"
     class="fixed bottom-4 right-4 z-50 max-w-sm"
     style="display: none;">
    <div class="rounded-lg shadow-lg p-4"
         :class="{
             'bg-blue-500 text-white': toastType === 'rent',
             'bg-orange-500 text-white': toastType === 'sale',
             'bg-gray-800 text-white': toastType === 'info'
         }">
        <div class="flex items-center gap-3">
            <div class="flex-1">
                <p class="text-sm font-medium" x-text="toastMessage"></p>
            </div>
            <button @click="showToast = false" class="text-white hover:text-gray-200" aria-label="Close notification">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
