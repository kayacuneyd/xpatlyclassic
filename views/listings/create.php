<?php
$useMap = false; // Load Leaflet lazily
require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Create New Listing</h1>

        <form method="POST"
              action="<?= url('listings/create') ?>"
              enctype="multipart/form-data"
              x-data="listingForm()"
              @submit.prevent="handleSubmit">
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
            <div x-show="currentStep === 0" class="space-y-6">
                <h2 class="text-xl font-semibold">Basic Information</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Category <span class="text-red-500">*</span></label>
                    <select name="category" required class="input" :class="fieldErrors.category ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <option value="">Select category</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="room">Room</option>
                    </select>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.category" x-text="fieldErrors.category"></p>
                </div>

                <div>
                    <label class="label">Deal Type <span class="text-red-500">*</span></label>
                    <select name="deal_type" required class="input" :class="fieldErrors.deal_type ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <option value="">Select deal type</option>
                        <option value="rent">For Rent</option>
                        <option value="sell">For Sale</option>
                    </select>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.deal_type" x-text="fieldErrors.deal_type"></p>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="label">Title (EN) <span class="text-red-500">*</span></label>
                        <input name="title_en"
                               required
                               class="input"
                               :class="fieldErrors.title_en ? 'border-red-400 ring-1 ring-red-300' : ''"
                               placeholder="Modern 2-bedroom apartment in city center"
                               minlength="10" maxlength="200">
                        <p class="text-xs text-gray-500 mt-1">Minimum 10 characters</p>
                        <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.title_en" x-text="fieldErrors.title_en"></p>
                    </div>
                    <div>
                        <label class="label">Description (EN) <span class="text-red-500">*</span></label>
                        <textarea name="description_en"
                                  required
                                  rows="6"
                                  class="input"
                                  :class="fieldErrors.description_en ? 'border-red-400 ring-1 ring-red-300' : ''"
                                  placeholder="Describe your property in detail..."
                                  minlength="50"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Minimum 50 characters</p>
                        <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.description_en" x-text="fieldErrors.description_en"></p>
                    </div>
                </div>
            </div>

            <!-- Step 2: Location -->
            <div x-show="currentStep === 1" class="space-y-6">
                <h2 class="text-xl font-semibold">Location</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Region <span class="text-red-500">*</span></label>
                    <select name="region" required class="input" @change="updateSettlements($event); debouncedGeocode()"
                            :class="fieldErrors.region ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <option value="">Select region</option>
                        <option value="Tallinn">Tallinn</option>
                        <option value="Tartu">Tartu</option>
                    </select>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.region" x-text="fieldErrors.region"></p>
                </div>

                <div>
                    <label class="label">District (Optional)</label>
                    <select name="settlement" class="input" x-ref="settlement"
                            x-model="settlementValue"
                            @change="handleSettlementChange($event); debouncedGeocode()"
                            :class="fieldErrors.settlement ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <option value="">Select district (optional)</option>
                    </select>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.settlement" x-text="fieldErrors.settlement"></p>
                </div>

                <div x-show="showOtherSettlement" x-cloak>
                    <label class="label">Other District (Optional)</label>
                    <input type="text" name="settlement_other" class="input"
                           x-model="settlementOtherValue"
                           placeholder="Type your district or neighborhood (optional)"
                           @input="debouncedGeocode()"
                           @blur="geocodeAddress(true)">
                </div>

                <div>
                    <label class="label">Full Address <span class="text-red-500">*</span></label>
                    <input type="text" name="address" required class="input"
                           :class="fieldErrors.address ? 'border-red-400 ring-1 ring-red-300' : ''"
                           placeholder="Street name, building number, apartment"
                           @input="debouncedGeocode()"
                           @blur="geocodeAddress(true)">
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.address" x-text="fieldErrors.address"></p>
                    <p class="text-xs text-gray-500 mt-1">Address will be pinned on the map automatically.</p>
                </div>

                <!-- Map -->
                <div>
                    <label class="label">Pin Location on Map (Optional)</label>
                    <div id="map" class="h-96 rounded-lg border-2 border-gray-300"></div>
                    <input type="hidden" name="latitude" x-ref="latitude">
                    <input type="hidden" name="longitude" x-ref="longitude">
                    <p class="text-xs text-gray-500 mt-1">Click on the map to set property location</p>
                    <p class="text-xs mt-1" x-show="geocodeStatus" x-text="geocodeStatus"></p>
                </div>
            </div>

            <!-- Step 3: Details -->
            <div x-show="currentStep === 2" class="space-y-6">
                <h2 class="text-xl font-semibold">Property Details</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Number of Rooms <span class="text-red-500">*</span></label>
                        <input type="number" name="rooms" required min="1" max="20" class="input" placeholder="3"
                               :class="fieldErrors.rooms ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.rooms" x-text="fieldErrors.rooms"></p>
                    </div>

                    <div>
                        <label class="label">Area (m²) <span class="text-red-500">*</span></label>
                        <input type="number" name="area_sqm" required min="1" max="500" step="0.01" class="input" placeholder="75"
                               :class="fieldErrors.area_sqm ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.area_sqm" x-text="fieldErrors.area_sqm"></p>
                    </div>
                </div>

                <div>
                    <label class="label">Condition <span class="text-red-500">*</span></label>
                    <select name="condition" required class="input" :class="fieldErrors.condition ? 'border-red-400 ring-1 ring-red-300' : ''">
                        <option value="">Select condition</option>
                        <option value="new_development">New Development</option>
                        <option value="good">Good Condition</option>
                        <option value="renovated">Renovated</option>
                        <option value="needs_renovation">Needs Renovation</option>
                    </select>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.condition" x-text="fieldErrors.condition"></p>
                </div>

                <div>
                    <label class="label">Extras</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="flex items-center">
                            <input type="checkbox" name="balcony" value="1" class="mr-2">
                            <span>Balcony</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="garage" value="1" class="mr-2">
                            <span>Garage</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="sauna" value="1" class="mr-2">
                            <span>Sauna</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="elevator" value="1" class="mr-2">
                            <span>Elevator</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="fireplace" value="1" class="mr-2">
                            <span>Fireplace</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="parking" value="1" class="mr-2">
                            <span>Parking</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="storage_room" value="1" class="mr-2">
                            <span>Storage Room</span>
                        </label>
                        <label class="flex items-center">
                            <input type="checkbox" name="pets_allowed" value="1" class="mr-2">
                            <span>Pets Allowed</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Step 4: Media -->
            <div x-show="currentStep === 3" class="space-y-6">
                <h2 class="text-xl font-semibold">Photos & Video</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Upload Images (1-10) <span class="text-red-500">*</span></label>
                    <input type="file" name="images[]" multiple accept="image/png,image/jpeg"
                           class="input" @change="previewImages($event)" required
                           x-ref="images"
                           :class="fieldErrors.images ? 'border-red-400 ring-1 ring-red-300' : ''">
                    <input type="hidden" name="primary_image_index" x-ref="primaryIndex" :value="primaryIndex">
                    <input type="hidden" name="save_draft" x-ref="saveDraft" value="0">
                    <p class="text-xs text-gray-500 mt-1">PNG or JPG, max 5MB each. Maximum 10 images.</p>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.images" x-text="fieldErrors.images"></p>
                </div>

                <!-- Image Preview -->
                <div x-ref="imagePreview" class="flex flex-wrap gap-3"></div>
                <p class="text-xs text-gray-500 mt-2">Click a thumbnail to preview. Use “Set Primary” to choose the main photo.</p>

                <div>
                    <label class="label">YouTube Video URL (Optional)</label>
                    <input type="url" name="youtube_url" class="input"
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>

            <!-- Step 5: Pricing -->
            <div x-show="currentStep === 4" class="space-y-6">
                <h2 class="text-xl font-semibold">Pricing</h2>
                <p class="text-sm text-red-600">* Required fields</p>

                <div>
                    <label class="label">Price (€) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" required min="1" step="0.01" class="input"
                           :class="fieldErrors.price ? 'border-red-400 ring-1 ring-red-300' : ''"
                           placeholder="850">
                    <p class="text-xs text-gray-500 mt-1">Monthly price for rent, total price for sale</p>
                    <p class="text-sm text-red-600 mt-1" x-show="fieldErrors.price" x-text="fieldErrors.price"></p>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h3 class="font-semibold text-blue-900 mb-2">💚 Expat-Friendly Pledge</h3>
                    <label class="flex items-start">
                        <input type="checkbox" name="expat_friendly" value="1" class="mt-1 mr-3">
                        <span class="text-sm text-blue-800">
                            I pledge that this property is available for expats without discrimination.
                            By checking this box, your listing will be marked as "Expat-Friendly" and shown prominently in search results.
                        </span>
                    </label>
                </div>
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8 pt-6 border-t">
                <button type="button" @click="currentStep > 0 && currentStep--"
                        :disabled="currentStep === 0"
                        :class="currentStep === 0 ? 'opacity-50 cursor-not-allowed' : ''"
                        class="btn btn-secondary">
                    ← Previous
                </button>

                <button type="button" @click="saveDraft()"
                        class="btn border border-gray-300 text-gray-700 bg-white hover:bg-gray-50">
                    Save Draft
                </button>

                <button type="button" @click="nextStep()"
                        x-show="currentStep < 4"
                        class="btn btn-primary">
                    Next →
                </button>

                <button type="submit" x-show="currentStep === 4" class="btn btn-primary inline-flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Publish Listing
                </button>
            </div>

            <!-- Image Preview Modal -->
            <div x-show="previewModalOpen"
                 x-cloak
                 @keydown.escape.window="closePreview()"
                 class="fixed inset-0 bg-black/70 z-50 flex items-center justify-center p-4"
                 style="display: none;">
                <div class="relative max-w-5xl w-full" @click.away="closePreview()">
                    <button type="button"
                            class="absolute -top-10 right-0 text-white text-2xl"
                            @click="closePreview()"
                            aria-label="Close preview">
                        ✕
                    </button>
                    <img :src="previewModalSrc"
                         alt="Preview"
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
        fieldErrors: {},
        stepError: '',
        geocodeStatus: '',
        geocodeTimer: null,
        pendingGeocode: false,
        lastGeocodeQuery: '',
        lastGeocodeSuccessQuery: '',
        geocodeRequestId: 0,
        settlementValue: '',
        settlementOtherValue: '',
        showOtherSettlement: false,
        primaryIndex: 0,
        selectedFiles: [],
        previewUrls: [],
        previewModalOpen: false,
        previewModalSrc: '',

        init() {
            this.formEl = this.$el instanceof HTMLFormElement ? this.$el : this.$el.closest('form');
            this.$watch('currentStep', (value) => {
                if (value === 1) {
                    this.initMap();
                }
            });
            this.settlementValue = this.getValue('settlement');
            this.settlementOtherValue = this.getValue('settlement_other');
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

                this.map.on('click', (e) => {
                    if (this.marker) {
                        this.map.removeLayer(this.marker);
                    }
                    this.marker = L.marker([e.latlng.lat, e.latlng.lng]).addTo(this.map);
                    this.$refs.latitude.value = e.latlng.lat;
                    this.$refs.longitude.value = e.latlng.lng;
                });

                setTimeout(() => this.map.invalidateSize(), 150);
                if (this.pendingGeocode) {
                    this.pendingGeocode = false;
                    this.geocodeAddress();
                }
            }).catch(() => {});
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
                    this.marker = L.marker([lat, lng]).addTo(this.map);
                    this.map.setView([lat, lng], 15);
                    this.$refs.latitude.value = lat;
                    this.$refs.longitude.value = lng;
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

        updateSettlements(event) {
            const region = event.target.value;
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
                    select.appendChild(option);
                });
            }

            const otherOption = document.createElement('option');
            otherOption.value = 'other';
            otherOption.textContent = 'Other';
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
            this.fieldErrors.images = '';

            if (files.length === 0 && this.selectedFiles.length === 0) {
                return;
            }

            const merged = this.selectedFiles.concat(files);
            if (merged.length > 10) {
                this.fieldErrors.images = 'Maximum 10 images allowed.';
            }
            this.selectedFiles = merged.slice(0, 10);
            this.previewUrls = [];

            const dt = new DataTransfer();
            this.selectedFiles.forEach((file) => dt.items.add(file));
            input.files = dt.files;

            if (this.primaryIndex >= this.selectedFiles.length) {
                this.primaryIndex = 0;
            }
            if (this.$refs.primaryIndex) {
                this.$refs.primaryIndex.value = String(this.primaryIndex);
            }

            this.selectedFiles.forEach((file, i) => {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.previewUrls[i] = e.target.result;
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-32 h-32 object-cover rounded-lg cursor-pointer">
                        <div class="absolute top-2 left-2 flex items-center gap-2">
                            <button type="button"
                                    class="text-xs px-2 py-1 rounded ${i === this.primaryIndex ? 'bg-green-600 text-white' : 'bg-white text-gray-700 border'}"
                                    data-index="${i}">
                                ${i === this.primaryIndex ? 'Primary' : 'Set Primary'}
                            </button>
                        </div>
                    `;
                    preview.appendChild(div);

                    const button = div.querySelector('button[data-index]');
                    if (button) {
                        button.addEventListener('click', () => {
                            this.primaryIndex = i;
                            if (this.$refs.primaryIndex) {
                                this.$refs.primaryIndex.value = String(i);
                            }
                            preview.querySelectorAll('button[data-index]').forEach((btn) => {
                                const idx = parseInt(btn.getAttribute('data-index') || '0', 10);
                                if (idx === i) {
                                    btn.classList.remove('bg-white', 'text-gray-700', 'border');
                                    btn.classList.add('bg-green-600', 'text-white');
                                    btn.textContent = 'Primary';
                                } else {
                                    btn.classList.remove('bg-green-600', 'text-white');
                                    btn.classList.add('bg-white', 'text-gray-700', 'border');
                                    btn.textContent = 'Set Primary';
                                }
                            });
                        });
                    }

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

        closePreview() {
            this.previewModalOpen = false;
            this.previewModalSrc = '';
        },

        nextStep() {
            if (this.validateStep(this.currentStep)) {
                this.currentStep++;
                this.scrollToTop();
            }
        },

        scrollToTop() {
            if (this.$el) {
                this.$el.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        },

        clearErrors() {
            this.fieldErrors = {};
            this.stepError = '';
        },

        setError(key, message) {
            if (!this.fieldErrors[key]) {
                this.fieldErrors[key] = message;
            }
            if (!this.stepError) {
                this.stepError = message;
            }
        },

        getField(name) {
            return this.$el.querySelector(`[name="${name}"]`);
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

        validateStep(step) {
            this.clearErrors();
            let firstInvalid = null;

            const requireText = (name, message) => {
                const el = this.getField(name);
                const value = this.getValue(name) || '';
                if (!value) {
                    this.setError(name, message);
                    if (!firstInvalid && el) firstInvalid = el;
                    return false;
                }
                return true;
            };

            if (step === 0) {
                requireText('category', 'Category is required.');
                requireText('deal_type', 'Deal type is required.');

                const titleEl = this.getField('title_en');
                const descEl = this.getField('description_en');
                const title = this.getValue('title_en') || '';
                const desc = this.getValue('description_en') || '';

                if (!title || title.length < 10) {
                    this.setError('title_en', 'English title must be at least 10 characters.');
                    if (!firstInvalid && titleEl) firstInvalid = titleEl;
                }

                if (!desc || desc.length < 50) {
                    this.setError('description_en', 'English description must be at least 50 characters.');
                    if (!firstInvalid && descEl) firstInvalid = descEl;
                }
            }

            if (step === 1) {
                requireText('region', 'Region is required.');
                requireText('address', 'Full address is required.');
            }

            if (step === 2) {
                requireText('rooms', 'Number of rooms is required.');
                requireText('area_sqm', 'Area is required.');
                requireText('condition', 'Condition is required.');
            }

            if (step === 3) {
                const count = this.selectedFiles.length;
                const hasFiles = count > 0;
                if (!hasFiles) {
                    this.setError('images', 'Please upload at least 1 image.');
                    if (!firstInvalid && this.$refs.images) firstInvalid = this.$refs.images;
                } else if (count > 10) {
                    this.setError('images', 'Maximum 10 images allowed.');
                    if (!firstInvalid && this.$refs.images) firstInvalid = this.$refs.images;
                }
            }

            if (step === 4) {
                requireText('price', 'Price is required.');
            }

            if (firstInvalid) {
                firstInvalid.focus();
            }

            return Object.keys(this.fieldErrors).length === 0;
        },

        handleSubmit() {
            if (this.$refs.saveDraft) {
                this.$refs.saveDraft.value = '0';
            }
            for (let step = 0; step < this.steps.length; step++) {
                if (!this.validateStep(step)) {
                    this.currentStep = step;
                    this.scrollToTop();
                    return;
                }
            }

            const formTarget = this.formEl || this.$el;
            if (formTarget instanceof HTMLFormElement) {
                formTarget.submit();
            }
        },

        validateDraft() {
            this.clearErrors();
            let firstInvalid = null;

            const requiredDraft = [
                { name: 'category', message: 'Category is required to save a draft.' },
                { name: 'deal_type', message: 'Deal type is required to save a draft.' },
                { name: 'title_en', message: 'English title is required to save a draft.' },
            ];

            requiredDraft.forEach((field) => {
                const el = this.getField(field.name);
                const value = this.getValue(field.name) || '';
                if (!value) {
                    this.setError(field.name, field.message);
                    if (!firstInvalid && el) firstInvalid = el;
                }
            });

            if (firstInvalid) {
                firstInvalid.focus();
            }

            return Object.keys(this.fieldErrors).length === 0;
        },

        saveDraft() {
            if (!this.validateDraft()) {
                this.currentStep = 0;
                this.scrollToTop();
                return;
            }
            if (this.$refs.saveDraft) {
                this.$refs.saveDraft.value = '1';
            }
            const formTarget = this.formEl || this.$el;
            if (formTarget instanceof HTMLFormElement) {
                formTarget.submit();
            }
        }
    }
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
