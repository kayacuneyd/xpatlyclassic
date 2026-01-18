<?php
require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6">
            <a href="<?= url('admin/listings') ?>"
               class="inline-flex items-center text-primary-600 hover:text-primary-800 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Listings
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit Listing</h1>
            <p class="text-sm text-gray-600 mt-1">Listing ID: <?= $listing['id'] ?></p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm text-gray-600">
                <div>
                    <div class="text-gray-500">Owner</div>
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($listing['user_id']) ?></div>
                </div>
                <div>
                    <div class="text-gray-500">Status</div>
                    <div class="font-medium text-gray-900"><?= ucfirst($listing['status']) ?></div>
                </div>
                <div>
                    <div class="text-gray-500">Region</div>
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($listing['region'] ?? '-') ?></div>
                </div>
                <div>
                    <div class="text-gray-500">District</div>
                    <div class="font-medium text-gray-900"><?= htmlspecialchars($listing['settlement'] ?? '-') ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($images)): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Current Images</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <?php foreach ($images as $image): ?>
                        <img src="/uploads/listings/<?= $listing['id'] ?>/<?= htmlspecialchars($image['filename']) ?>"
                             alt="Listing image"
                             class="w-full h-32 object-cover rounded-lg border border-gray-200">
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="<?= url('admin/listings/' . $listing['id'] . '/edit') ?>" class="space-y-6">
                <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Title *</label>
                    <input type="text" id="title" name="title" required
                           value="<?= htmlspecialchars($listing['title'] ?? '') ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                    <textarea id="description" name="description" rows="6" required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"><?= htmlspecialchars($listing['description'] ?? '') ?></textarea>
                </div>

                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Price (€) *</label>
                    <input type="number" id="price" name="price" step="0.01" min="1" required
                           value="<?= htmlspecialchars((string) ($listing['price'] ?? '')) ?>"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="expat_friendly" value="1"
                               <?= !empty($listing['expat_friendly']) ? 'checked' : '' ?>
                               class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                        Expat-Friendly
                    </label>
                    <label class="flex items-center gap-2">
                        <input type="checkbox" name="pets_allowed" value="1"
                               <?= !empty($listing['pets_allowed']) ? 'checked' : '' ?>
                               class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                        Pets Allowed
                    </label>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Extras</h3>
                    <?php
                    $extrasList = [
                        'balcony' => 'Balcony',
                        'garage' => 'Garage',
                        'sauna' => 'Sauna',
                        'elevator' => 'Elevator',
                        'fireplace' => 'Fireplace',
                        'parking' => 'Parking',
                        'storage_room' => 'Storage Room'
                    ];
                    ?>
                    <div class="grid grid-cols-2 gap-3">
                        <?php foreach ($extrasList as $key => $label): ?>
                            <label class="flex items-center gap-2">
                                <input type="checkbox" name="<?= $key ?>" value="1"
                                       <?= !empty($extras[$key]) ? 'checked' : '' ?>
                                       class="rounded text-primary-500 focus:ring-primary-500 border-gray-300">
                                <?= $label ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200">
                    <a href="<?= url('admin/listings') ?>" class="btn btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
