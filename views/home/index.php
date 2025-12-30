<?php
$hideHero = true;
$pageTitle = __('home.hero_title') ?? 'Find Your Perfect Home in Estonia';
require __DIR__ . '/../layouts/header.php';
?>

<!-- Hero Section -->
<section class="relative overflow-hidden" style="min-height: 600px; background-image: url('/hero-bg.webp'); background-size: cover; background-position: center; margin: 3rem 3rem 0 3rem; border-radius: 1.5rem;">
    <!-- Background Overlay -->
    <div class="absolute inset-0 z-0" style="background: linear-gradient(to right, rgba(17, 24, 39, 0.8) 0%, rgba(17, 24, 39, 0.6) 50%, rgba(17, 24, 39, 0.4) 100%); border-radius: 1.5rem;"></div>

    <!-- Content -->
    <div class="max-w-7xl mx-auto px-8 relative z-10" style="padding-top: 2.5rem; padding-bottom: 2.5rem; min-height: 600px; display: flex; align-items: center;">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 items-center">

            <!-- Left Column: Text (60%) -->
            <div class="lg:col-span-3 space-y-8 text-center lg:text-left">
                <!-- Headings -->
                <div class="space-y-4">
                    <h1 class="text-5xl lg:text-7xl font-bold tracking-tight text-white leading-[1.1]">
                        <?= __('home.hero_title') ?? 'Find Your Perfect Home in Estonia' ?>
                    </h1>
                    <p class="text-xl text-gray-200 max-w-2xl mx-auto lg:mx-0 leading-relaxed font-light">
                        <?= __('home.hero_subtitle') ?? 'Expat-friendly properties without discrimination.' ?>
                    </p>
                </div>

                <!-- CTA Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-4">
                    <a href="<?= url('listings') ?>"
                        class="inline-flex items-center justify-center px-8 py-4 text-lg font-bold text-white rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-200"
                        style="background: linear-gradient(135deg, #f9a825 0%, #f57f17 100%);">
                        <?= __('home.browse_listings') ?? 'Browse Listings' ?>
                        <svg class="w-5 h-5 ml-2 -mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-3 gap-8 pt-8 border-t border-white/30 mt-8">
                    <div>
                        <div class="text-3xl font-bold text-white">500+</div>
                        <div class="text-sm text-gray-300 font-medium">
                            <?= __('home.active_listings') ?? 'Active Listings' ?></div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Quick Search Card (40%) -->
            <div class="lg:col-span-2">
                <form action="<?= url('listings') ?>" method="GET"
                    class="bg-white rounded-2xl p-6 shadow-2xl relative z-20"
                    style="box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);" x-data="{
                          showFilters: false,
                          dealType: '',
                          priceMin: 0,
                          priceMax: 5000,
                          roomsMin: 1,
                          roomsMax: 6,

                          // Dynamic limits based on deal type
                          get maxPriceLimit() {
                              return this.dealType === 'sell' ? 500000 : 5000;
                          },
                          get priceStep() {
                              return this.dealType === 'sell' ? 5000 : 50;
                          },

                          formatPrice(val) {
                              return '€' + Number(val).toLocaleString();
                          },

                          init() {
                              this.$watch('dealType', (val) => {
                                  if (val === 'sell') {
                                      this.priceMax = 500000;
                                      this.priceMin = 0;
                                  } else {
                                      this.priceMax = 5000;
                                      this.priceMin = 0;
                                  }
                              });
                          }
                      }">

                    <h3 class="text-gray-900 font-heading text-xl font-bold mb-5 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-600" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <?= __('home.quick_search') ?? 'Quick Search' ?>
                    </h3>

                    <div class="space-y-4">
                        <!-- Deal Type & Property Type Row -->
                        <div class="grid grid-cols-2 gap-4">
                            <select name="deal_type" x-model="dealType"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('listing.deal_type') ?? 'Deal Type' ?></option>
                                <option value="rent"><?= __('listing.rent') ?? 'Rent' ?></option>
                                <option value="sell"><?= __('listing.sale') ?? 'Sale' ?></option>
                            </select>
                            <select name="category"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('listing.category') ?? 'Type' ?></option>
                                <option value="apartment"><?= __('listing.apartment') ?? 'Apartment' ?></option>
                                <option value="house"><?= __('listing.house') ?? 'House' ?></option>
                                <option value="room"><?= __('listing.room') ?? 'Room' ?></option>
                            </select>
                        </div>

                        <!-- Region -->
                        <select name="region"
                            class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                            <option value=""><?= __('listing.region') ?? 'All Regions' ?></option>
                            <option value="Tallinn">Tallinn</option>
                            <option value="Tartu">Tartu</option>
                            <option value="Pärnu">Pärnu</option>
                            <option value="Narva">Narva</option>
                        </select>

                        <!-- Price Range Slider (Alpine.js) -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex justify-between items-center mb-2">
                                <label
                                    class="text-xs font-bold text-gray-700 uppercase tracking-wide"><?= __('search.price_range') ?? 'Price' ?></label>
                                <div class="text-xs font-bold text-primary-600">
                                    <span x-text="formatPrice(priceMin)"></span> - <span
                                        x-text="formatPrice(priceMax)"></span>
                                </div>
                            </div>

                            <!-- Native visible sliders stack -->
                            <div class="flex gap-4">
                                <input type="range" x-model="priceMin" min="0" :max="maxPriceLimit" :step="priceStep"
                                    class="w-full accent-primary-600">
                                <input type="range" x-model="priceMax" min="0" :max="maxPriceLimit" :step="priceStep"
                                    class="w-full accent-primary-600">
                            </div>

                            <div class="flex items-center space-x-3 mt-3">
                                <div class="relative flex-1">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                                    <input type="number" name="price_min" x-model="priceMin"
                                        class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center">
                                </div>
                                <span class="text-gray-400">-</span>
                                <div class="relative flex-1">
                                    <span
                                        class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm">€</span>
                                    <input type="number" name="price_max" x-model="priceMax"
                                        class="w-full pl-7 pr-2 py-2 text-sm border border-gray-200 rounded-lg focus:ring-1 focus:ring-primary-500 text-center">
                                </div>
                            </div>
                        </div>

                        <!-- More Filters Toggle -->
                        <button type="button" @click="showFilters = !showFilters"
                            class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-600 hover:text-gray-900 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                                </svg>
                                <?= __('search.more_filters') ?? 'More Filters' ?>
                            </span>
                            <svg class="w-4 h-4 transition-transform duration-200"
                                :class="{ 'rotate-180': showFilters }" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Collapsible Filters -->
                        <div x-show="showFilters" x-collapse class="space-y-4 pt-2">
                            <!-- Rooms Range -->
                            <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                                <div class="flex justify-between items-center mb-2">
                                    <label
                                        class="text-xs font-bold text-gray-700 uppercase tracking-wide"><?= __('search.rooms') ?? 'Rooms' ?></label>
                                    <div class="text-xs font-bold text-primary-600">
                                        <span x-text="roomsMin"></span> - <span x-text="roomsMax"></span>
                                    </div>
                                </div>
                                <div class="flex gap-4">
                                    <input type="range" x-model="roomsMin" min="1" max="6"
                                        class="w-full accent-primary-600">
                                    <input type="range" x-model="roomsMax" min="1" max="6"
                                        class="w-full accent-primary-600">
                                </div>
                                <div class="flex items-center space-x-3 mt-3">
                                    <input type="number" name="rooms_min" x-model="roomsMin"
                                        class="w-full flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg text-center">
                                    <span class="text-gray-400">-</span>
                                    <input type="number" name="rooms_max" x-model="roomsMax"
                                        class="w-full flex-1 px-3 py-2 text-sm border border-gray-200 rounded-lg text-center">
                                </div>
                            </div>

                            <!-- Condition -->
                            <select name="condition"
                                class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors">
                                <option value=""><?= __('search.condition') ?? 'Condition' ?></option>
                                <option value="new_development"><?= __('search.new_development') ?? 'New Development' ?>
                                </option>
                                <option value="renovated"><?= __('search.renovated') ?? 'Renovated' ?></option>
                                <option value="good"><?= __('search.good_condition') ?? 'Good Condition' ?></option>
                                <option value="needs_renovation">
                                    <?= __('search.needs_renovation') ?? 'Needs Renovation' ?>
                                </option>
                            </select>

                            <!-- Extras Checkboxes -->
                            <div class="grid grid-cols-2 gap-3 text-sm">
                                <label
                                    class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="extras[]" value="balcony"
                                        class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                    <?= __('search.balcony') ?? 'Balcony' ?>
                                </label>
                                <label
                                    class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="extras[]" value="garage"
                                        class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                    <?= __('search.garage') ?? 'Garage' ?>
                                </label>
                                <label
                                    class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="extras[]" value="elevator"
                                        class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                    <?= __('search.elevator') ?? 'Elevator' ?>
                                </label>
                                <label
                                    class="flex items-center gap-2 px-3 py-2 bg-white border border-gray-200 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                    <input type="checkbox" name="extras[]" value="pets_allowed"
                                        class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                    <?= __('search.pets_allowed') ?? 'Pets OK' ?>
                                </label>
                            </div>
                        </div>

                        <!-- Search Button -->
                        <button type="submit"
                            class="w-full py-4 text-base font-bold rounded-xl flex items-center justify-center gap-2 transition-all hover:shadow-lg transform active:scale-[0.98]"
                            style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: #ffffff;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                            </svg>
                            <?= __('common.search') ?? 'Search' ?>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <h2 class="text-3xl font-bold text-gray-900 mb-4"><?= __('home.get_started') ?? 'Get started' ?></h2>
            <p class="text-lg text-gray-600"><?= __('home.steps_subtitle') ?? 'Just a few steps to book a stay.' ?></p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
            <!-- Step 1 -->
            <div class="flex flex-col items-center text-center group">
                <div class="relative w-24 h-24 mb-6 transition-transform group-hover:scale-110">
                    <div class="absolute inset-0 bg-primary-100 rounded-full opacity-50"></div>
                    <div class="relative flex items-center justify-center w-full h-full text-primary-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-600 text-white text-sm font-bold flex items-center justify-center rounded-full border-2 border-white shadow-md">
                        01
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('home.step_1_title') ?? 'Find a Property' ?>
                </h3>
                <p class="text-gray-600 leading-relaxed">
                    <?= __('home.step_1_desc') ?? 'Aliquam pretium fringilla augue orci dictum sollicitudin purus suscipit risus.' ?>
                </p>
            </div>

            <!-- Step 2 -->
            <div class="flex flex-col items-center text-center group">
                <div class="relative w-24 h-24 mb-6 transition-transform group-hover:scale-110">
                    <div class="absolute inset-0 bg-primary-100 rounded-full opacity-50"></div>
                    <div class="relative flex items-center justify-center w-full h-full text-primary-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-600 text-white text-sm font-bold flex items-center justify-center rounded-full border-2 border-white shadow-md">
                        02
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('home.step_2_title') ?? 'Check Reviews' ?></h3>
                <p class="text-gray-600 leading-relaxed">
                    <?= __('home.step_2_desc') ?? 'Etiam vehicula erat id lorem volutpat cursus placerat dignissim rutrum efficitur.' ?>
                </p>
            </div>

            <!-- Step 3 -->
            <div class="flex flex-col items-center text-center group">
                <div class="relative w-24 h-24 mb-6 transition-transform group-hover:scale-110">
                    <div class="absolute inset-0 bg-primary-100 rounded-full opacity-50"></div>
                    <div class="relative flex items-center justify-center w-full h-full text-primary-600">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div
                        class="absolute -top-2 -right-2 w-8 h-8 bg-secondary-600 text-white text-sm font-bold flex items-center justify-center rounded-full border-2 border-white shadow-md">
                        03
                    </div>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3"><?= __('home.step_3_title') ?? 'Book a Stay' ?></h3>
                <p class="text-gray-600 leading-relaxed">
                    <?= __('home.step_3_desc') ?? 'Mauris sit amet fermentum felis cras rutrum vestibulum diam accumsan cursus.' ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Role Selection (Rent vs Host) - New CTA Style (Primary Color Background) -->
<section class="py-12 border-b border-gray-100 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- I want to Rent -->
            <a href="<?= url('listings') ?>"
                class="group relative overflow-hidden rounded-2xl p-8 transition-all duration-300 shadow-sm hover:shadow-md"
                style="background-color: #f9a825;">
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2"><?= __('home.role_rent_title') ?? 'I want to Rent' ?>
                    </h3>
                    <p class="text-white/90 mb-6">
                        <?= __('home.role_rent_desc') ?? 'Find your perfect home from verified listings.' ?></p>
                    <span class="inline-flex items-center font-semibold text-white group-hover:underline">
                        <?= __('home.role_rent_btn') ?? 'Browse Homes' ?> <span class="ml-2">→</span>
                    </span>
                </div>
            </a>

            <!-- I want to Host (Owner) -->
            <a href="<?= url('listings/create') ?>"
                class="group relative overflow-hidden rounded-2xl p-8 transition-all duration-300 shadow-sm hover:shadow-md"
                style="background-color: #1976d2;">
                <div class="relative z-10 flex flex-col items-center text-center">
                    <div
                        class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 transition-transform group-hover:scale-110">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2"><?= __('home.role_host_title') ?? 'I am an Owner' ?>
                    </h3>
                    <p class="text-white/90 mb-6">
                        <?= __('home.role_host_desc') ?? 'List your property for free and find reliable tenants.' ?></p>
                    <span class="inline-flex items-center font-semibold text-white group-hover:underline">
                        <?= __('home.role_host_btn') ?? 'Post a Listing' ?> <span class="ml-2">→</span>
                    </span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-end mb-12">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 mb-2">
                    <?= __('home.featured_listings') ?? 'Featured Listings' ?></h2>
                <p class="text-gray-600"><?= __('home.featured_subtitle') ?? 'Hand-picked properties for you' ?></p>
            </div>
            <a href="<?= url('listings') ?>"
                class="text-primary-600 font-semibold hover:text-primary-700 hidden sm:block">
                <?= __('home.view_all') ?? 'View All Listings' ?> →
            </a>
        </div>

        <?php if (empty($featuredListings)): ?>
            <div class="text-center py-12">
                <p class="text-gray-500"><?= __('home.no_featured') ?? 'No featured listings at the moment.' ?></p>
            </div>
        <?php else: ?>
            <!-- Carousel Container -->
            <div class="relative" x-data="{
                currentIndex: 0,
                itemsPerPage: window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1),
                totalItems: <?= count($featuredListings) ?>,
                get maxIndex() {
                    return Math.max(0, this.totalItems - this.itemsPerPage);
                },
                next() {
                    if (this.currentIndex < this.maxIndex) {
                        this.currentIndex++;
                    } else {
                        this.currentIndex = 0;
                    }
                },
                prev() {
                    if (this.currentIndex > 0) {
                        this.currentIndex--;
                    } else {
                        this.currentIndex = this.maxIndex;
                    }
                },
                init() {
                    window.addEventListener('resize', () => {
                        this.itemsPerPage = window.innerWidth >= 1024 ? 3 : (window.innerWidth >= 768 ? 2 : 1);
                        if (this.currentIndex > this.maxIndex) {
                            this.currentIndex = this.maxIndex;
                        }
                    });
                }
            }">
                <!-- Previous Button -->
                <button @click="prev()"
                    class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 z-10 w-12 h-12 bg-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    :class="{ 'opacity-50 cursor-not-allowed': totalItems <= itemsPerPage }">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>

                <!-- Next Button -->
                <button @click="next()"
                    class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 z-10 w-12 h-12 bg-white rounded-full shadow-lg hover:shadow-xl flex items-center justify-center transition-all duration-200 hover:scale-110 focus:outline-none focus:ring-2 focus:ring-primary-500"
                    :class="{ 'opacity-50 cursor-not-allowed': totalItems <= itemsPerPage }">
                    <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>

                <!-- Carousel Track -->
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-out gap-8"
                        :style="`transform: translateX(-${currentIndex * (100 / itemsPerPage)}%)`">
                        <?php foreach ($featuredListings as $listing): ?>
                            <div class="flex-shrink-0 w-full md:w-1/2 lg:w-1/3 px-4">
                                <a href="<?= url("listings/{$listing['id']}") ?>"
                                    class="group bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden block">
                                    <div class="relative aspect-[4/3] overflow-hidden">
                                        <?php if (!empty($listing['primary_image'])): ?>
                                            <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $listing['primary_image'] ?>"
                                                alt="<?= htmlspecialchars($listing['title']) ?>"
                                                class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                                                loading="lazy">
                                        <?php else: ?>
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        <?php endif; ?>

                                        <div class="absolute top-4 right-4">
                                            <span
                                                class="px-3 py-1 bg-white/90 backdrop-blur text-xs font-bold text-gray-900 rounded-full shadow-sm">
                                                <?= ucfirst($listing['deal_type']) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3
                                                class="text-lg font-bold text-gray-900 line-clamp-1 group-hover:text-primary-600 transition-colors">
                                                <?= htmlspecialchars($listing['title']) ?>
                                            </h3>
                                        </div>

                                        <p class="text-gray-500 text-sm mb-4 flex items-center">
                                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                                        </p>

                                        <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                                            <div class="flex items-center gap-4 text-sm text-gray-600">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                    </svg>
                                                    <?= $listing['rooms'] ?>
                                                </span>
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" />
                                                    </svg>
                                                    <?= (int) $listing['area_sqm'] ?> m²
                                                </span>
                                            </div>
                                            <span class="text-xl font-bold text-primary-600">€<?= number_format($listing['price']) ?></span>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Dots Indicator -->
                <div class="flex justify-center gap-2 mt-8">
                    <template x-for="i in Math.ceil(totalItems / itemsPerPage)" :key="i">
                        <button @click="currentIndex = i - 1"
                            class="w-2 h-2 rounded-full transition-all duration-200"
                            :class="currentIndex === i - 1 ? 'bg-primary-600 w-8' : 'bg-gray-300 hover:bg-gray-400'">
                        </button>
                    </template>
                </div>
            </div>
        <?php endif; ?>

        <div class="mt-8 text-center sm:hidden">
            <a href="<?= url('listings') ?>"
                class="inline-block px-6 py-3 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                <?= __('home.view_all') ?? 'View All Listings' ?>
            </a>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>