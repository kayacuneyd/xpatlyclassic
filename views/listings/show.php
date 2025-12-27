<?php
$useMap = true; // Enable Leaflet.js
require __DIR__ . '/../layouts/header.php';
?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Image Gallery -->
            <div class="bg-white rounded-lg shadow-lg overflow-hidden mb-6" x-data="{ currentImage: 0 }">
                <?php if (!empty($images)): ?>
                    <!-- Main Image -->
                    <div class="relative h-96 bg-gray-200">
                        <template x-for="(image, index) in <?= count($images) ?>" :key="index">
                            <img x-show="currentImage === index"
                                 src="/uploads/listings/<?= $listing['id'] ?>/<?= $images[0]['filename'] ?>"
                                 :src="`/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars(json_encode(array_column($images, 'filename'))) ?>[index]`"
                                 alt="<?= htmlspecialchars($listing['title']) ?>"
                                 class="w-full h-full object-cover">
                        </template>

                        <!-- Navigation Arrows -->
                        <?php if (count($images) > 1): ?>
                            <button @click="currentImage = currentImage > 0 ? currentImage - 1 : <?= count($images) - 1 ?>"
                                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                                ←
                            </button>
                            <button @click="currentImage = currentImage < <?= count($images) - 1 ?> ? currentImage + 1 : 0"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-black/50 text-white p-2 rounded-full hover:bg-black/70">
                                →
                            </button>
                        <?php endif; ?>

                        <!-- Image Counter -->
                        <div class="absolute bottom-4 right-4 bg-black/70 text-white px-3 py-1 rounded">
                            <span x-text="currentImage + 1"></span> / <?= count($images) ?>
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
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <!-- Title & Badge -->
                <div class="mb-4">
                    <?php if ($listing['expat_friendly']): ?>
                        <span class="badge-expat mb-2">✓ Expat-Friendly</span>
                    <?php endif; ?>
                    <h1 class="text-3xl font-bold text-gray-900 mt-2">
                        <?= htmlspecialchars($listing['title']) ?>
                    </h1>
                    <p class="text-gray-600 mt-2">
                        📍 <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                    </p>
                </div>

                <!-- Key Details -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 p-4 bg-gray-50 rounded-lg">
                    <div>
                        <div class="text-sm text-gray-600">Price</div>
                        <div class="text-xl font-bold text-primary-600">€<?= number_format($listing['price'], 0) ?>/mo</div>
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
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Description</h2>
                    <p class="text-gray-700 whitespace-pre-line"><?= htmlspecialchars($listing['description']) ?></p>
                </div>

                <!-- Property Details -->
                <div class="mb-6">
                    <h2 class="text-xl font-semibold mb-3">Property Details</h2>
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
                        <h2 class="text-xl font-semibold mb-3">Amenities</h2>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($extras as $key => $value): ?>
                                <?php if ($value): ?>
                                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">
                                        ✓ <?= ucfirst(str_replace('_', ' ', $key)) ?>
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
                                const map = L.map('map').setView([<?= $listing['latitude'] ?>, <?= $listing['longitude'] ?>], 15);

                                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                }).addTo(map);

                                L.marker([<?= $listing['latitude'] ?>, <?= $listing['longitude'] ?>])
                                    .addTo(map)
                                    .bindPopup('<?= htmlspecialchars($listing['title']) ?>')
                                    .openPopup();
                            });
                        </script>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="lg:col-span-1">
            <!-- Contact Form -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 sticky top-4">
                <h2 class="text-xl font-semibold mb-4">Contact Owner</h2>

                <form method="POST" action="/listings/<?= $listing['id'] ?>/message" class="space-y-4">
                    <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

                    <div>
                        <label class="label">Your Name (Optional)</label>
                        <input type="text" name="sender_name" class="input" placeholder="John Doe">
                    </div>

                    <div>
                        <label class="label">Your Email *</label>
                        <input type="email" name="sender_email" required class="input" placeholder="your@email.com">
                    </div>

                    <div>
                        <label class="label">Message *</label>
                        <textarea name="message" required rows="5" class="input"
                                  placeholder="I'm interested in this property..."></textarea>
                    </div>

                    <button type="submit" class="w-full btn btn-primary">
                        Send Message
                    </button>
                </form>

                <!-- Actions -->
                <div class="mt-6 space-y-2">
                    <?php if (Core\Auth::check()): ?>
                        <form method="POST" action="/favorites/<?= $listing['id'] ?>/toggle" class="w-full">
                            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">
                            <button type="submit" class="w-full btn btn-secondary">
                                <?= $isFavorited ? '❤️ Saved' : '🤍 Save to Favorites' ?>
                            </button>
                        </form>
                    <?php endif; ?>

                    <button onclick="navigator.share ? navigator.share({title: '<?= htmlspecialchars($listing['title']) ?>', url: window.location.href}) : alert('Share this link: ' + window.location.href)"
                            class="w-full btn btn-secondary">
                        🔗 Share
                    </button>

                    <button @click="$refs.reportModal.classList.remove('hidden')"
                            class="w-full btn btn-secondary text-red-600 hover:bg-red-50">
                        🚩 Report Listing
                    </button>
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

<!-- Report Modal -->
<div x-ref="reportModal" class="hidden fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg max-w-md w-full p-6" @click.away="$refs.reportModal.classList.add('hidden')">
        <h2 class="text-2xl font-bold mb-4">Report Listing</h2>

        <form method="POST" action="/listings/<?= $listing['id'] ?>/report">
            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

            <div class="mb-4">
                <label class="label">Your Email (Optional)</label>
                <input type="email" name="email" class="input">
            </div>

            <div class="mb-4">
                <label class="label">Reason for Reporting *</label>
                <textarea name="reason" required rows="4" class="input"
                          placeholder="Please describe why you're reporting this listing..."></textarea>
            </div>

            <div class="flex gap-2">
                <button type="button" @click="$refs.reportModal.classList.add('hidden')"
                        class="btn btn-secondary flex-1">
                    Cancel
                </button>
                <button type="submit" class="btn btn-danger flex-1">
                    Submit Report
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
