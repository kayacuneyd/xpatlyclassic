<?php
$useMap = true;
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

                <div>
                    <label class="label">Category *</label>
                    <select name="category" required class="input">
                        <option value="">Select category</option>
                        <option value="apartment">Apartment</option>
                        <option value="house">House</option>
                        <option value="room">Room</option>
                    </select>
                </div>

                <div>
                    <label class="label">Deal Type *</label>
                    <select name="deal_type" required class="input">
                        <option value="">Select deal type</option>
                        <option value="rent">For Rent</option>
                        <option value="sell">For Sale</option>
                    </select>
                </div>

                <div class="space-y-4">
                    <div class="flex gap-2">
                        <template x-for="lang in languages" :key="lang">
                            <button type="button"
                                    @click="activeLang = lang"
                                    :class="activeLang === lang ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700'"
                                    class="px-3 py-1 rounded-md border border-gray-200 text-sm">
                                <span x-text="lang.toUpperCase()"></span>
                            </button>
                        </template>
                    </div>

                    <template x-for="lang in languages" :key="lang">
                        <div x-show="activeLang === lang" x-cloak class="space-y-4">
                            <div>
                                <label class="label" x-text="labels[lang].title"></label>
                                <input :name="`title_${lang}`"
                                       :required="false"
                                       class="input"
                                       :placeholder="labels[lang].title_placeholder"
                                       minlength="10" maxlength="200">
                                <p class="text-xs text-gray-500 mt-1">Minimum 10 characters (EN required)</p>
                            </div>
                            <div>
                                <label class="label" x-text="labels[lang].description"></label>
                                <textarea :name="`description_${lang}`"
                                          :required="false"
                                          rows="6"
                                          class="input"
                                          :placeholder="labels[lang].description_placeholder"
                                          minlength="50"></textarea>
                                <p class="text-xs text-gray-500 mt-1">Minimum 50 characters (EN required)</p>
                            </div>
                        </div>
                    </template>
                    <template x-if="errors.title || errors.description">
                        <div class="text-sm text-red-600">
                            <div x-text="errors.title"></div>
                            <div x-text="errors.description"></div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Step 2: Location -->
            <div x-show="currentStep === 1" class="space-y-6">
                <h2 class="text-xl font-semibold">Location</h2>

                <div>
                    <label class="label">Region *</label>
                    <select name="region" required class="input" @change="updateSettlements($event)">
                        <option value="">Select region</option>
                        <option value="Tallinn">Tallinn</option>
                        <option value="Tartu">Tartu</option>
                    </select>
                </div>

                <div>
                    <label class="label">Settlement *</label>
                    <select name="settlement" required class="input" x-ref="settlement">
                        <option value="">Select region first</option>
                    </select>
                </div>

                <div>
                    <label class="label">Full Address *</label>
                    <input type="text" name="address" required class="input"
                           placeholder="Street name, building number, apartment">
                </div>

                <!-- Map -->
                <div>
                    <label class="label">Pin Location on Map (Optional)</label>
                    <div id="map" class="h-96 rounded-lg border-2 border-gray-300"></div>
                    <input type="hidden" name="latitude" x-ref="latitude">
                    <input type="hidden" name="longitude" x-ref="longitude">
                    <p class="text-xs text-gray-500 mt-1">Click on the map to set property location</p>
                </div>
            </div>

            <!-- Step 3: Details -->
            <div x-show="currentStep === 2" class="space-y-6">
                <h2 class="text-xl font-semibold">Property Details</h2>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="label">Number of Rooms *</label>
                        <input type="number" name="rooms" required min="1" max="20" class="input" placeholder="3">
                    </div>

                    <div>
                        <label class="label">Area (m²) *</label>
                        <input type="number" name="area_sqm" required min="1" max="500" step="0.01" class="input" placeholder="75">
                    </div>
                </div>

                <div>
                    <label class="label">Condition *</label>
                    <select name="condition" required class="input">
                        <option value="">Select condition</option>
                        <option value="new_development">New Development</option>
                        <option value="good">Good Condition</option>
                        <option value="renovated">Renovated</option>
                        <option value="needs_renovation">Needs Renovation</option>
                    </select>
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

                <div>
                    <label class="label">Upload Images (1-40) *</label>
                    <input type="file" name="images[]" multiple accept="image/png,image/jpeg"
                           class="input" @change="previewImages($event)" required>
                    <p class="text-xs text-gray-500 mt-1">PNG or JPG, max 5MB each</p>
                </div>

                <!-- Image Preview -->
                <div x-ref="imagePreview" class="grid grid-cols-2 md:grid-cols-4 gap-4"></div>

                <div>
                    <label class="label">YouTube Video URL (Optional)</label>
                    <input type="url" name="youtube_url" class="input"
                           placeholder="https://www.youtube.com/watch?v=...">
                </div>
            </div>

            <!-- Step 5: Pricing -->
            <div x-show="currentStep === 4" class="space-y-6">
                <h2 class="text-xl font-semibold">Pricing</h2>

                <div>
                    <label class="label">Price (€) *</label>
                    <input type="number" name="price" required min="1" step="0.01" class="input"
                           placeholder="850">
                    <p class="text-xs text-gray-500 mt-1">Monthly price for rent, total price for sale</p>
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

                <button type="button" @click="currentStep < 4 && currentStep++"
                        x-show="currentStep < 4"
                        class="btn btn-primary">
                    Next →
                </button>

                <button type="submit" x-show="currentStep === 4" class="btn btn-primary">
                    🚀 Publish Listing
                </button>
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
        languages: ['en', 'et', 'ru'],
        activeLang: 'en',
        errors: {
            title: '',
            description: ''
        },
        labels: {
            en: {
                title: 'Title (EN)',
                description: 'Description (EN)',
                title_placeholder: 'Modern 2-bedroom apartment in city center',
                description_placeholder: 'Describe your property in detail...'
            },
            et: {
                title: 'Pealkiri (ET)',
                description: 'Kirjeldus (ET)',
                title_placeholder: 'Kaasaegne 2-toaline korter kesklinnas',
                description_placeholder: 'Kirjelda kinnisvara üksikasjalikult...'
            },
            ru: {
                title: 'Заголовок (RU)',
                description: 'Описание (RU)',
                title_placeholder: 'Современная 2-комнатная квартира в центре',
                description_placeholder: 'Подробно опишите объект...'
            }
        },

        init() {
            // Initialize map
            setTimeout(() => {
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
            }, 500);
        },

        updateSettlements(event) {
            const region = event.target.value;
            const settlements = {
                'Tallinn': ['Lasnamäe', 'Kesklinn', 'Kristiine', 'Mustamäe', 'Nõmme', 'Pirita', 'Põhja-Tallinn', 'Haabersti'],
                'Tartu': ['Kesklinn', 'Annelinn', 'Ränilinn', 'Ihaste', 'Raadi', 'Karlova']
            };

            const select = this.$refs.settlement;
            select.innerHTML = '<option value="">Select settlement</option>';

            if (region && settlements[region]) {
                settlements[region].forEach(settlement => {
                    const option = document.createElement('option');
                    option.value = settlement;
                    option.textContent = settlement;
                    select.appendChild(option);
                });
            }
        },

        previewImages(event) {
            const files = event.target.files;
            const preview = this.$refs.imagePreview;
            preview.innerHTML = '';

            for (let i = 0; i < Math.min(files.length, 40); i++) {
                const file = files[i];
                const reader = new FileReader();

                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                        ${i === 0 ? '<span class="absolute top-2 left-2 bg-green-500 text-white text-xs px-2 py-1 rounded">Primary</span>' : ''}
                    `;
                    preview.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
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
                this.activeLang = 'en';
                titleEl?.focus();
                return;
            }

            if (!desc || desc.length < 50) {
                this.errors.description = 'Please enter at least 50 characters for the English description.';
                this.activeLang = 'en';
                descEl?.focus();
                return;
            }

            this.$el.submit();
        }
    }
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
