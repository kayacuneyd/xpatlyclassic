<?php
$useMap = false; // Load Leaflet lazily
require __DIR__ . '/../layouts/header.php';

// Ensure images is always an array
$images = $images ?? [];
$imageFilenames = !empty($images) ? array_column($images, 'filename') : [];
$imageFilenamesJson = json_encode($imageFilenames, JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS);
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image Gallery -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden mb-8"
                 x-data='{"currentImage": 0, "images": <?= $imageFilenamesJson ?>}'>
                <?php if (!empty($images) && count($images) > 0): ?>
                    <!-- Main Image -->
                    <div class="relative h-96 bg-gray-200">
                        <template x-for="(image, index) in images" :key="index">
                            <img x-show="currentImage === index"
                                 :src="`/uploads/listings/<?= $listing['id'] ?>/${image}`"
                                 alt="<?= htmlspecialchars($listing['title']) ?>"
                                 class="w-full h-full object-cover"
                                 width="1280" height="720">
                        </template>

                        <!-- Navigation Arrows -->
                        <template x-if="images.length > 1">
                            <div>
                                <button @click="currentImage = currentImage > 0 ? currentImage - 1 : images.length - 1"
                                        class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/60 text-white w-12 h-12 rounded-full hover:bg-black/75 flex items-center justify-center"
                                        aria-label="Previous image">
                                    ←
                                </button>
                                <button @click="currentImage = currentImage < images.length - 1 ? currentImage + 1 : 0"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/60 text-white w-12 h-12 rounded-full hover:bg-black/75 flex items-center justify-center"
                                        aria-label="Next image">
                                    →
                                </button>
                            </div>
                        </template>

                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded">
                            <span x-text="currentImage + 1"></span> / <span x-text="images.length"></span>
                        </div>
                    </div>

                    <!-- Thumbnails -->
                    <?php if (count($images) > 1): ?>
                        <div class="flex gap-2 p-4 overflow-x-auto">
                            <?php foreach ($images as $index => $image): ?>
                                <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($image['filename']) ?>"
                                     @click="currentImage = <?= $index ?>"
                                     :class="currentImage === <?= $index ?> ? 'ring-2 ring-primary-600' : ''"
                                     class="w-20 h-20 object-cover rounded cursor-pointer hover:opacity-80"
                                     width="80" height="80"
                                     alt="Thumbnail <?= $index + 1 ?>">
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="h-96 bg-gray-200 flex items-center justify-center">
                        <p class="text-gray-400">No images available</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Listing Details -->
            <div class="bg-white rounded-2xl shadow-2xl p-8 mb-8">
                <!-- Title & Badge -->
                <div class="mb-6">
                    <?php if ($listing['expat_friendly']): ?>
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-500 text-white text-sm font-bold rounded-full mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Expat-Friendly
                        </span>
                    <?php endif; ?>
                    <h1 class="text-4xl font-bold text-gray-900 mt-2 mb-3">
                        <?= htmlspecialchars($listing['title']) ?>
                    </h1>
                    <p class="text-gray-600 flex items-center text-lg">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                    </p>
                </div>

                <!-- Key Details -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-8 p-6 bg-gray-50 rounded-xl border border-gray-100">
                    <div>
                        <div class="text-sm text-gray-600">Price</div>
                        <div class="text-xl font-bold text-secondary-700">€<?= number_format($listing['price'], 0) ?>/mo</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Area</div>
                        <div class="text-xl font-bold"><?= number_format($listing['area_sqm'], 0) ?> m²</div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">Rooms</div>
                        <div class="text-xl font-bold"><?= $listing['rooms'] ?></div>
                    </div>
                    <div>
                        <div class="text-sm text-gray-600">€/m²</div>
                        <div class="text-xl font-bold">€<?= number_format($listing['price_per_sqm'], 0) ?></div>
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line leading-relaxed"><?= htmlspecialchars($listing['description']) ?></p>
                </div>

                <!-- Property Details -->
                <div class="mb-8">
                    <h2 class="text-2xl font-bold mb-4">Property Details</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium"><?= ucfirst($listing['category']) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Deal Type:</span>
                            <span class="font-medium"><?= ucfirst($listing['deal_type']) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Condition:</span>
                            <span class="font-medium"><?= str_replace('_', ' ', ucfirst($listing['condition'])) ?></span>
                        </div>
                        <div>
                            <span class="text-gray-600">Pets Allowed:</span>
                            <span class="font-medium"><?= $listing['pets_allowed'] ? 'Yes' : 'No' ?></span>
                        </div>
                    </div>
                </div>

                <!-- Extras -->
                <?php if (!empty($extras) && array_filter($extras)): ?>
                    <div class="mb-6">
                        <h2 class="text-2xl font-bold mb-4">Amenities</h2>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($extras as $key => $value): ?>
                                <?php if ($value): ?>
                                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 border border-green-200 rounded-lg text-sm font-medium">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <?= ucfirst(str_replace('_', ' ', $key)) ?>
                                    </span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- YouTube Video -->
                <?php if (!empty($listing['youtube_url'])): ?>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-3">Video Tour</h2>
                        <div class="aspect-w-16 aspect-h-9">
                            <?php
                            $videoId = '';
                            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\?]+)/', $listing['youtube_url'], $matches)) {
                                $videoId = $matches[1];
                            }
                            ?>
                            <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($videoId) ?>"
                                    class="w-full h-96 rounded-lg"
                                    frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Map -->
                <?php if (!empty($listing['latitude']) && !empty($listing['longitude'])): ?>
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold mb-3">Location</h2>
                        <div id="map" class="h-96 rounded-lg"></div>
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

                                loadLeaflet().then(function() {
                                    const map = L.map('map').setView([<?= $listing['latitude'] ?>, <?= $listing['longitude'] ?>], 15);

                                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                    }).addTo(map);

                                    L.marker([<?= $listing['latitude'] ?>, <?= $listing['longitude'] ?>])
                                        .addTo(map)
                                        .bindPopup('<?= htmlspecialchars($listing['title']) ?>')
                                        .openPopup();
                                }).catch(() => {});
                            });
                        </script>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-2xl p-6 mb-6 sticky top-4">
                <h2 class="text-2xl font-bold mb-6">Contact Owner</h2>

                <form method="POST" action="/listings/<?= $listing['id'] ?>/message" class="space-y-4">
                    <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

                    <div>
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block">Your Name (Optional)</label>
                        <input type="text" name="sender_name" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors" placeholder="John Doe" <?= Core\Auth::check() ? 'value="' . htmlspecialchars(\Core\Auth::user()['full_name'] ?? '') . '"' : '' ?>>
                    </div>

                    <?php if (Core\Auth::check()): ?>
                        <input type="hidden" name="sender_email" value="<?= htmlspecialchars(\Core\Auth::user()['email']) ?>">
                        <div class="text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded-xl p-3">
                            Messages will be sent from your account email: <strong><?= htmlspecialchars(\Core\Auth::user()['email']) ?></strong>
                        </div>
                    <?php else: ?>
                    <div>
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block">Your Email *</label>
                        <input type="email" name="sender_email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors" placeholder="your@email.com">
                    </div>
                    <?php endif; ?>

                    <div>
                        <label class="text-xs font-bold text-gray-700 uppercase tracking-wide mb-2 block">Message *</label>
                        <textarea name="message" required rows="5" class="w-full px-4 py-3 border border-gray-200 rounded-xl text-gray-900 text-sm focus:ring-2 focus:ring-primary-500 bg-gray-50 focus:bg-white transition-colors"
                                  placeholder="I'm interested in this property..."></textarea>
                    </div>

                    <button type="submit" class="w-full py-4 text-base font-bold rounded-xl flex items-center justify-center gap-2 transition-all hover:shadow-lg transform active:scale-[0.98]" style="background: linear-gradient(135deg, #1976d2 0%, #0d47a1 100%); color: #ffffff;">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Send Message
                    </button>
                </form>

                <!-- Actions -->
                <div class="mt-6 space-y-3">
                    <?php if (Core\Auth::check()): ?>
                        <form method="POST" action="/favorites/<?= $listing['id'] ?>/toggle" class="w-full">
                            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">
                            <button type="submit" class="w-full py-3 px-4 border border-gray-300 rounded-xl <?= $isFavorited ? 'bg-red-50 text-red-600 border-red-200' : 'text-gray-700' ?> font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="<?= $isFavorited ? 'currentColor' : 'none' ?>" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                                <?= $isFavorited ? 'Saved' : 'Save to Favorites' ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <button onclick="navigator.share ? navigator.share({title: '<?= htmlspecialchars($listing['title']) ?>', url: window.location.href}) : alert('Share this link: ' + window.location.href)"
                            class="w-full py-3 px-4 border border-gray-300 rounded-xl text-gray-700 font-medium hover:bg-gray-50 transition-colors flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Share
                    </button>

                    <!-- Report Button with Alpine.js State -->
                    <div x-data="{ showReportModal: false }">
                        <button @click="showReportModal = true"
                                class="w-full py-3 px-4 border border-red-200 rounded-xl text-red-600 font-medium hover:bg-red-50 transition-colors flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"/>
                            </svg>
                            <?= __('listing.report') ?? 'Report Listing' ?>
                        </button>

                        <!-- Report Modal -->
                        <div x-show="showReportModal"
                             x-cloak
                             @keydown.escape.window="showReportModal = false"
                             class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4"
                             style="display: none; z-index: 2000;">
                            <div @click.away="showReportModal = false"
                                 x-transition
                                 class="bg-white rounded-lg max-w-md w-full p-6">
                                <h2 class="text-2xl font-bold mb-4"><?= __('listing.report') ?? 'Report Listing' ?></h2>

                                <form method="POST" action="/listings/<?= $listing['id'] ?>/report">
                                    <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

                                    <div class="mb-4">
                                        <label class="label"><?= __('listing.reporter_email') ?? 'Your Email (Optional)' ?></label>
                                        <input type="email" name="email" class="input">
                                    </div>

                                    <div class="mb-4">
                                        <label class="label"><?= __('listing.report_reason') ?? 'Reason for Reporting' ?> *</label>
                                        <textarea name="reason" required rows="4" class="input"
                                                  placeholder="<?= __('listing.report_placeholder') ?? 'Please describe why you\'re reporting this listing...' ?>"></textarea>
                                        <p class="text-xs text-gray-500 mt-1"><?= __('listing.report_note') ?? 'Minimum 10 characters' ?></p>
                                    </div>

                                    <div class="flex gap-2">
                                        <button type="button" @click="showReportModal = false"
                                                class="btn btn-secondary flex-1">
                                            <?= __('common.cancel') ?? 'Cancel' ?>
                                        </button>
                                        <button type="submit" class="btn btn-danger flex-1">
                                            <?= __('common.submit') ?? 'Submit Report' ?>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Owner Info -->
                <div class="mt-6 pt-6 border-t">
                    <p class="text-sm text-gray-600">Listed by</p>
                    <p class="font-medium"><?= htmlspecialchars($listing['owner_name']) ?></p>
                    <p class="text-xs text-gray-500 mt-1">
                        Posted: <?= date('M d, Y', strtotime($listing['created_at'])) ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
