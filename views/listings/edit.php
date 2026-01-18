<?php
$useMap = false; // Load Leaflet lazily
require __DIR__ . '/../layouts/header.php';

$settlementsByRegion = [
    'Tallinn' => ['Kesklinn', 'Kristiine', 'Mustamäe', 'Lasnamäe', 'Põhja-Tallinn'],
    'Tartu' => ['Kesklinn', 'Annelinn', 'Karlova', 'Supilinn', 'Ihaste'],
];
$currentRegion = $listing['region'] ?? '';
$currentSettlement = $listing['settlement'] ?? '';
$knownSettlements = $settlementsByRegion[$currentRegion] ?? [];
$isOtherSettlement = $currentSettlement !== '' && !in_array($currentSettlement, $knownSettlements, true);
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Edit Listing</h1>

        <form method="POST" action="<?= url('listings/' . $listing['id'] . '/edit') ?>" enctype="multipart/form-data"
            x-data="listingForm()" x-init="currentStep = 0" @submit.prevent="handleSubmit"
            @open-preview.window="openPreviewSrc($event.detail.src)">
            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

            <!-- Step Indicator -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <template x-for="(step, index) in steps" :key="index">
                        <div class="flex items-center">
                            <div :class="currentStep >= index ? 'bg-primary-600 text-white' : 'bg-gray-300 text-gray-600'"
                                class="w-10 h-10 rounded-full flex items-center justify-center font-semibold">
                                <span x-text="index + 1"></span>
                            </div>
                            <div x-show="index < steps.length - 1" class="w-16 h-1 bg-gray-300 mx-2"></div>
                        </div>
                    </template>
                </div>
                <div class="text-center mt-2 font-medium text-gray-700" x-text="steps[currentStep]"></div>
            </div>

            <!-- Step 1: Basic Info -->
            <div x-show="currentStep == 0" class="space-y-6">
                <h2 class="text-xl font-semibold">Basic Information</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Category *</label>
                    <select name="category" required class="input">
                        <option value="">Select category</option>
                        <option value="apartment" <?= $listing['category'] === 'apartment' ? 'selected' : '' ?>>Apartment
                        </option>
                        <option value="house" <?= $listing['category'] === 'house' ? 'selected' : '' ?>>House</option>
                        <option value="room" <?= $listing['category'] === 'room' ? 'selected' : '' ?>>Room</option>
                    </select>
                </div>

                <div>
                    <label class="label">Deal Type *</label>
                    <select name="deal_type" required class="input">
                        <option value="">Select deal type</option>
                        <option value="rent" <?= $listing['deal_type'] === 'rent' ? 'selected' : '' ?>>For Rent</option>
                        <option value="sell" <?= $listing['deal_type'] === 'sell' ? 'selected' : '' ?>>For Sale</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="label">Title (EN) *</label>
                        <input name="title_en" required class="input" minlength="10" maxlength="200"
                            value="<?= htmlspecialchars($listing['title'] ?? '') ?>">
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                        <p class="text-sm text-red-600 mt-1" x-show="errors.title" x-text="errors.title"></p>
                    </div>
                    <div>
                        <label class="label">Description (EN) *</label>
                        <textarea name="description_en" required rows="6" class="input"
                            minlength="50"><?= htmlspecialchars($listing['description'] ?? '') ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                        <p class="text-sm text-red-600 mt-1" x-show="errors.description" x-text="errors.description">
                        </p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Location -->
            <div x-show="currentStep == 1" class="space-y-6">
                <h2 class="text-xl font-semibold">Location</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Region *</label>
                    <select name="region" required class="input"
                        @change="updateSettlements($event); handleSettlementChange($event); debouncedGeocode()">
                        <option value="">Select region</option>
                        <option value="Tallinn" <?= $currentRegion === 'Tallinn' ? 'selected' : '' ?>>Tallinn</option>
                        <option value="Tartu" <?= $currentRegion === 'Tartu' ? 'selected' : '' ?>>Tartu</option>
                    </select>
                </div>

                <div>
                    <label class="label">District (Optional)</label>
                    <select name="settlement" class="input" x-ref="settlement"
                        x-model="settlementValue"
                        @change="handleSettlementChange($event); debouncedGeocode()">
                        <option value="">Select district (optional)</option>
                        <?php if (!empty($currentRegion) && isset($settlementsByRegion[$currentRegion])): ?>
                            <?php foreach ($settlementsByRegion[$currentRegion] as $settlement): ?>
                                <option value="<?= htmlspecialchars($settlement) ?>" <?= $settlement === $currentSettlement ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($settlement) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <option value="other" <?= $isOtherSettlement ? 'selected' : '' ?>>Other</option>
                    </select>
                </div>

                <div x-show="showOtherSettlement" x-cloak>
                    <label class="label">Other District (Optional)</label>
                    <input type="text" name="settlement_other" class="input"
                        x-model="settlementOtherValue"
                        placeholder="Type your district or neighborhood (optional)"
                        value="<?= $isOtherSettlement ? htmlspecialchars($currentSettlement) : '' ?>"
                        @input="debouncedGeocode()"
                        @blur="geocodeAddress(true)">
                </div>

                <div>
                    <label class="label">Full Address *</label>
                    <input type="text" name="address" required class="input"
                        placeholder="Street name, building number, apartment" @input="debouncedGeocode()"
                        @blur="geocodeAddress(true)" value="<?= htmlspecialchars($listing['address'] ?? '') ?>">
                </div>

                <!-- Map -->
                <div>
                    <label class="label">Pin Location on Map (Optional)</label>
                    <div id="map" class="h-96 rounded-lg border-2 border-gray-300"></div>
                    <input type="hidden" name="latitude" x-ref="latitude"
                        value="<?= htmlspecialchars($listing['latitude'] ?? '') ?>">
                    <input type="hidden" name="longitude" x-ref="longitude"
                        value="<?= htmlspecialchars($listing['longitude'] ?? '') ?>">
                    <p class="text-xs text-gray-500 mt-1">Click on the map to set property location</p>
                    <p class="text-xs mt-1" x-show="geocodeStatus" x-text="geocodeStatus"></p>
                </div>
            </div>

            <!-- Step 3: Details -->
            <div x-show="currentStep == 2" class="space-y-6">
                <h2 class="text-xl font-semibold">Property Details</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Number of Rooms *</label>
                        <input type="number" name="rooms" required min="1" max="20" class="input" placeholder="3"
                            value="<?= htmlspecialchars((string) ($listing['rooms'] ?? '')) ?>">
                    </div>

                    <div>
                        <label class="label">Area (m²) *</label>
                        <input type="number" name="area_sqm" required min="1" max="500" step="0.01" class="input"
                            placeholder="75" value="<?= htmlspecialchars((string) ($listing['area_sqm'] ?? '')) ?>">
                    </div>
                </div>

                <div>
                    <label class="label">Condition *</label>
                    <select name="condition" required class="input">
                        <option value="">Select condition</option>
                        <option value="new_development" <?= ($listing['condition'] ?? '') === 'new_development' ? 'selected' : '' ?>>New Development</option>
                        <option value="good" <?= ($listing['condition'] ?? '') === 'good' ? 'selected' : '' ?>>Good
                            Condition</option>
                        <option value="renovated" <?= ($listing['condition'] ?? '') === 'renovated' ? 'selected' : '' ?>>
                            Renovated</option>
                        <option value="needs_renovation" <?= ($listing['condition'] ?? '') === 'needs_renovation' ? 'selected' : '' ?>>Needs Renovation</option>
                    </select>
                </div>

                <div>
                    <label class="label">Extras</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="balcony" value="1" class="mr-2" <?= !empty($extras['balcony']) ? 'checked' : '' ?>>
                            <span>Balcony</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="garage" value="1" class="mr-2" <?= !empty($extras['garage']) ? 'checked' : '' ?>>
                            <span>Garage</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sauna" value="1" class="mr-2" <?= !empty($extras['sauna']) ? 'checked' : '' ?>>
                            <span>Sauna</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="elevator" value="1" class="mr-2" <?= !empty($extras['elevator']) ? 'checked' : '' ?>>
                            <span>Elevator</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="fireplace" value="1" class="mr-2"
                                <?= !empty($extras['fireplace']) ? 'checked' : '' ?>>
                            <span>Fireplace</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="parking" value="1" class="mr-2" <?= !empty($extras['parking']) ? 'checked' : '' ?>>
                            <span>Parking</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="storage_room" value="1" class="mr-2"
                                <?= !empty($extras['storage_room']) ? 'checked' : '' ?>>
                            <span>Storage Room</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="pets_allowed" value="1" class="mr-2"
                                <?= !empty($listing['pets_allowed']) ? 'checked' : '' ?>>
                            <span>Pets Allowed</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Step 4: Media -->
            <div x-show="currentStep == 3" class="space-y-6">
                <h2 class="text-xl font-semibold">Photos & Video</h2>

                <?php if (!empty($images)): ?>
                    <div class="mb-6" x-data="{ deletedImages: [], primaryImageId: <?= $images[0]['id'] ?? 0 ?> }">
                        <h3 class="font-medium text-gray-700 mb-3">Current Images (<?= count($images) ?>)</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <?php foreach ($images as $image): ?>
                                <div class="relative group"
                                    :class="deletedImages.includes(<?= $image['id'] ?>) ? 'opacity-40' : ''">
                                    <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($image['filename']) ?>"
                                        alt="Listing image"
                                        class="w-full h-32 object-cover rounded-lg border border-gray-200 cursor-pointer"
                                        @click="$dispatch('open-preview', { src: '/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($image['filename']) ?>' })">

                                    <!-- Primary badge -->
                                    <span
                                        x-show="primaryImageId === <?= $image['id'] ?> && !deletedImages.includes(<?= $image['id'] ?>)"
                                        class="absolute top-2 left-2 bg-primary-600 text-white text-xs px-2 py-1 rounded">
                                        Primary
                                    </span>

                                    <!-- Delete Button -->
                                    <button type="button"
                                        @click="deletedImages.includes(<?= $image['id'] ?>) ? deletedImages = deletedImages.filter(id => id !== <?= $image['id'] ?>) : deletedImages.push(<?= $image['id'] ?>)"
                                        class="absolute top-2 right-2 bg-red-600 text-white p-1.5 rounded-full opacity-0 group-hover:opacity-100 transition-opacity hover:bg-red-700"
                                        :class="deletedImages.includes(<?= $image['id'] ?>) ? 'opacity-100' : ''">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>

                                    <!-- Set Primary Button (only show if not already primary and not deleted) -->
                                    <button type="button"
                                        x-show="primaryImageId !== <?= $image['id'] ?> && !deletedImages.includes(<?= $image['id'] ?>)"
                                        @click="primaryImageId = <?= $image['id'] ?>"
                                        class="absolute bottom-2 left-2 bg-white text-gray-700 px-2 py-1 rounded text-xs opacity-0 group-hover:opacity-100 transition-opacity hover:bg-gray-100 border border-gray-300">
                                        Set as Primary
                                    </button>

                                    <!-- Deleted overlay -->
                                    <div x-show="deletedImages.includes(<?= $image['id'] ?>)"
                                        class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center">
                                        <span class="text-white font-semibold text-sm">Will be deleted</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Hidden inputs to track changes -->
                        <template x-for="imageId in deletedImages" :key="imageId">
                            <input type="hidden" name="delete_images[]" :value="imageId">
                        </template>
                        <input type="hidden" name="primary_image_id" :value="primaryImageId">

                        <p class="text-xs text-gray-500 mt-2">
                            Click trash icon to mark for deletion. Click "Set as Primary" to change the main image.
                        </p>
                    </div>
                <?php endif; ?>

                <div>
                    <label class="label">Upload New Images (1-10) <?= empty($images) ? '*' : '(Optional)' ?></label>
                    <input type="file" name="images[]" multiple accept="image/png,image/jpeg" class="input"
                        @change="previewImages($event)" <?= empty($images) ? 'required' : '' ?> x-ref="images">
                    <p class="text-xs text-gray-500 mt-1">PNG or JPG, max 5MB each. Maximum 10 images total.</p>
                </div>

                <!-- Image Preview -->
                <div x-ref="imagePreview" class="flex flex-wrap gap-3"></div>
                <p class="text-xs text-gray-500 mt-2">Click a thumbnail to preview.</p>

                <div>
                    <label class="label">YouTube Video URL (Optional)</label>
                    <input type="url" name="youtube_url" class="input" placeholder="https://www.youtube.com/watch?v=..."
                        value="<?= htmlspecialchars($listing['youtube_url'] ?? '') ?>">
                </div>
            </div>

            <!-- Step 5: Pricing -->
            <div x-show="currentStep == 4" class="space-y-6">
                <h2 class="text-xl font-semibold">Pricing</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Price (€) *</label>
                    <input type="number" name="price" required min="1" step="0.01" class="input" placeholder="850"
                        value="<?= htmlspecialchars((string) ($listing['price'] ?? '')) ?>">
                    <p class="text-xs text-gray-500 mt-1">Monthly price for rent, total price for sale</p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">💚 Expat-Friendly Pledge</h3>
                    <label class="flex items-start">
                        <input type="checkbox" name="expat_friendly" value="1" class="mt-1 mr-3"
                            <?= !empty($listing['expat_friendly']) ? 'checked' : '' ?>>
                        <span class="text-sm text-blue-800">
                            I pledge that this property is available for expats without discrimination.
                            By checking this box, your listing will be marked as "Expat-Friendly" and shown prominently
                            in search results.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8 pt-6 border-t">
                <button type="button" @click="prevStep()" :disabled="currentStep === 0"
                    :class="currentStep === 0 ? 'opacity-50 cursor-not-allowed' : ''" class="btn btn-secondary">
                    ← Previous
                </button>

                <button type="button" @click="nextStep" x-show="currentStep < 4" class="btn btn-primary">
                    Next →
                </button>

                <button type="submit" x-show="currentStep === 4" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Update Listing
                </button>
            </div>

            <!-- Image Preview Modal -->
            <div x-show="previewModalOpen" x-cloak @keydown.escape.window="closePreview()"
                class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4" style="display: none;">
                <div class="relative max-w-5xl w-full" @click.away="closePreview()">
                    <button type="button" class="absolute -top-10 right-0 text-white text-2xl" @click="closePreview()"
                        aria-label="Close preview">
                        ✕
                    </button>
                    <img :src="previewModalSrc" alt="Preview"
                        class="w-full max-h-[80vh] object-contain rounded-lg bg-black">
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function listingForm() {
        return {
            currentStep: 0,
            steps: ['Basic Info', 'Location', 'Details', 'Media', 'Pricing'],
            map: null,
            marker: null,
            formEl: null,
            errors: {
                title: '',
                description: ''
            },
            geocodeStatus: '',
            geocodeTimer: null,
            pendingGeocode: false,
            lastGeocodeQuery: '',
            lastGeocodeSuccessQuery: '',
            geocodeRequestId: 0,
            settlementValue: <?= json_encode($isOtherSettlement ? 'other' : $currentSettlement) ?>,
            settlementOtherValue: <?= json_encode($isOtherSettlement ? $currentSettlement : '') ?>,
            showOtherSettlement: <?= $isOtherSettlement ? 'true' : 'false' ?>,
            selectedFiles: [],
            previewUrls: [],
            previewModalOpen: false,
            previewModalSrc: '',

            init() {
                this.formEl = this.$el instanceof HTMLFormElement ? this.$el : this.$el.closest('form');
                this.updateRequiredFields();
                this.$watch('currentStep', (value) => {
                    this.updateRequiredFields();
                    if (value === 1) {
                        this.initMap();
                    }
                });
                if (!this.settlementValue) {
                    this.settlementValue = this.getValue('settlement');
                }
                if (!this.settlementOtherValue) {
                    this.settlementOtherValue = this.getValue('settlement_other');
                }
                this.showOtherSettlement = this.settlementValue === 'other';
            },

            initMap() {
                const mapEl = document.getElementById('map');
                if (!mapEl) {
                    return;
                }

                if (this.map) {
                    setTimeout(() => this.map.invalidateSize(), 150);
                    return;
                }

                this.loadLeaflet().then(() => {
                    this.map = L.map('map').setView([59.437, 24.7536], 12);
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: '&copy; OpenStreetMap contributors'
                    }).addTo(this.map);

                    const lat = parseFloat(this.$el.querySelector('[name="latitude"]')?.value) || 59.437;
                    const lng = parseFloat(this.$el.querySelector('[name="longitude"]')?.value) || 24.7536;

                    this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                    this.map.setView([lat, lng], 13);

                    this.marker.on('dragend', (e) => {
                        const position = e.target.getLatLng();
                        this.$el.querySelector('[name="latitude"]').value = position.lat.toFixed(6);
                        this.$el.querySelector('[name="longitude"]').value = position.lng.toFixed(6);
                    });

                    this.map.on('click', (e) => {
                        this.marker.setLatLng(e.latlng);
                        this.$el.querySelector('[name="latitude"]').value = e.latlng.lat.toFixed(6);
                        this.$el.querySelector('[name="longitude"]').value = e.latlng.lng.toFixed(6);
                    });

                    setTimeout(() => this.map.invalidateSize(), 150);
                    if (this.pendingGeocode) {
                        this.pendingGeocode = false;
                        this.geocodeAddress();
                    }
                }).catch(() => { });
            },

            debouncedGeocode() {
                clearTimeout(this.geocodeTimer);
                this.geocodeTimer = setTimeout(() => this.geocodeAddress(false), 1200);
            },

            geocodeAddress(force = false) {
                const address = this.getValue('address');
                const region = this.getValue('region');
                const settlementRaw = this.getValue('settlement');
                const settlementOther = this.getValue('settlement_other');
                const settlement = settlementRaw === 'other' ? settlementOther : settlementRaw;
                if (!address || !region) {
                    return;
                }

                if (!this.map) {
                    this.pendingGeocode = true;
                    return;
                }

            const query = settlement
                ? `${address}, ${settlement}, ${region}, Estonia`
                : `${address}, ${region}, Estonia`;
            if (!force && (query === this.lastGeocodeQuery || query === this.lastGeocodeSuccessQuery)) {
                return;
            }
            this.lastGeocodeQuery = query;
            const url = `${window.location.origin}/geocode.php?q=${encodeURIComponent(query)}`;
            const requestId = ++this.geocodeRequestId;
            if (force) {
                this.geocodeStatus = 'Finding address on map...';
            } else {
                this.geocodeStatus = '';
            }

            fetch(url, { headers: { 'Accept': 'application/json' }, cache: 'no-store' })
                .then((response) => response.json())
                .then((results) => {
                    if (requestId !== this.geocodeRequestId) {
                        return;
                    }
                    if (results && !Array.isArray(results) && results.error) {
                        this.geocodeStatus = '';
                        return;
                    }
                    if (!results || !results.length) {
                        this.geocodeStatus = '';
                        return;
                    }
                        const lat = parseFloat(results[0].lat);
                        const lng = parseFloat(results[0].lon);
                        if (Number.isNaN(lat) || Number.isNaN(lng)) {
                            this.geocodeStatus = '';
                            return;
                        }

                        if (this.marker) {
                            this.map.removeLayer(this.marker);
                        }
                        this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
                        this.map.setView([lat, lng], 15);
                        this.$el.querySelector('[name="latitude"]').value = lat.toFixed(6);
                        this.$el.querySelector('[name="longitude"]').value = lng.toFixed(6);
                        this.lastGeocodeSuccessQuery = query;
                        this.geocodeStatus = 'Address pinned on the map.';
                    })
                    .catch(() => {
                        this.geocodeStatus = '';
                    });
            },

            loadLeaflet() {
                if (window.L) {
                    return Promise.resolve();
                }
                return new Promise((resolve, reject) => {
                    const link = document.createElement('link');
                    link.rel = 'stylesheet';
                    link.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
                    link.onload = () => {
                        const script = document.createElement('script');
                        script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
                        script.onload = resolve;
                        script.onerror = reject;
                        document.head.appendChild(script);
                    };
                    link.onerror = reject;
                    document.head.appendChild(link);
                });
            },

            updateRequiredFields() {
                // Remove required from all fields first
                this.$el.querySelectorAll('[required]').forEach(el => {
                    el.removeAttribute('required');
                });

                // Re-add required to fields that should be required in this step
                if (this.currentStep === 0) {
                    this.$el.querySelector('[name="category"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="deal_type"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="title_en"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="description_en"]')?.setAttribute('required', 'required');
                } else if (this.currentStep === 1) {
                    this.$el.querySelector('[name="region"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="address"]')?.setAttribute('required', 'required');
                } else if (this.currentStep === 2) {
                    this.$el.querySelector('[name="rooms"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="area_sqm"]')?.setAttribute('required', 'required');
                    this.$el.querySelector('[name="condition"]')?.setAttribute('required', 'required');
                }
            },

            nextStep() {
                if (this.currentStep < this.steps.length - 1) {
                    this.currentStep++;
                }
            },

            prevStep() {
                if (this.currentStep > 0) {
                    this.currentStep--;
                }
            },
            getValue(name) {
                const formTarget = this.formEl || this.$el;
                if (!(formTarget instanceof HTMLFormElement)) {
                    return '';
                }
                const formData = new FormData(formTarget);
                const value = formData.get(name);
                if (typeof value === 'string') {
                    return value.trim();
                }
                return value;
            },

            updateSettlements(event) {
                const region = event.target.value;
                this.populateSettlements(region);
            },

            populateSettlements(region, selectedSettlement = '') {
                const settlements = {
                    'Tallinn': ['Kesklinn', 'Kristiine', 'Mustamäe', 'Lasnamäe', 'Põhja-Tallinn'],
                    'Tartu': ['Kesklinn', 'Annelinn', 'Karlova', 'Supilinn', 'Ihaste']
                };

                const select = this.$refs.settlement;
                select.innerHTML = '<option value="">Select district (optional)</option>';

                if (region && settlements[region]) {
                    settlements[region].forEach(settlement => {
                        const option = document.createElement('option');
                        option.value = settlement;
                        option.textContent = settlement;
                        if (selectedSettlement && settlement === selectedSettlement) {
                            option.selected = true;
                        }
                        select.appendChild(option);
                    });
                }

                const otherOption = document.createElement('option');
                otherOption.value = 'other';
                otherOption.textContent = 'Other';
                if (selectedSettlement === 'other') {
                    otherOption.selected = true;
                }
                select.appendChild(otherOption);

                this.handleSettlementChange({ target: select });
            },

            handleSettlementChange(event) {
                const value = event.target.value;
                this.settlementValue = value;
                this.showOtherSettlement = value === 'other';
                if (!this.showOtherSettlement) {
                    this.settlementOtherValue = '';
                }
            },

            previewImages(event) {
                const input = event.target;
                const files = Array.from(input.files || []);
                const preview = this.$refs.imagePreview;
                preview.innerHTML = '';

                if (files.length === 0 && this.selectedFiles.length === 0) {
                    return;
                }

                const merged = this.selectedFiles.concat(files);
                this.selectedFiles = merged.slice(0, 10);
                this.previewUrls = [];

                const dt = new DataTransfer();
                this.selectedFiles.forEach((file) => dt.items.add(file));
                input.files = dt.files;

                this.selectedFiles.forEach((file, i) => {
                    const reader = new FileReader();

                    reader.onload = (e) => {
                        this.previewUrls[i] = e.target.result;
                        const div = document.createElement('div');
                        div.className = 'relative';
                        div.innerHTML = `
                        <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg cursor-pointer">
                    `;
                        preview.appendChild(div);

                        const imageEl = div.querySelector('img');
                        if (imageEl) {
                            imageEl.addEventListener('click', () => {
                                this.openPreview(i);
                            });
                        }
                    };

                    reader.readAsDataURL(file);
                });
            },

            openPreview(index) {
                const src = this.previewUrls[index];
                if (!src) {
                    return;
                }
                this.previewModalSrc = src;
                this.previewModalOpen = true;
            },

            openPreviewSrc(src) {
                if (!src) {
                    return;
                }
                this.previewModalSrc = src;
                this.previewModalOpen = true;
            },

            closePreview() {
                this.previewModalOpen = false;
                this.previewModalSrc = '';
            },

            handleSubmit() {
                this.errors.title = '';
                this.errors.description = '';

                const titleEl = this.$el.querySelector('[name="title_en"]');
                const descEl = this.$el.querySelector('[name="description_en"]');
                const title = titleEl ? titleEl.value.trim() : '';
                const desc = descEl ? descEl.value.trim() : '';

                if (!title || title.length < 10) {
                    this.errors.title = 'Please enter at least 10 characters for the English title.';
                    titleEl?.focus();
                    return;
                }

                if (!desc || desc.length < 50) {
                    this.errors.description = 'Please enter at least 50 characters for the English description.';
                    descEl?.focus();
                    return;
                }

                this.$el.submit();
            }
        }
    }
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
