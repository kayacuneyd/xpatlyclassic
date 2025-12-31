<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-gray-50 min-h-screen py-10">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?= __('user.favorites') ?></h1>
        </div>

        <?php if (empty($favorites)): ?>
            <div class="bg-white rounded-lg shadow p-6 text-center text-gray-600">
                <?= __('user.no_favorites') ?>
            </div>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($favorites as $listing): ?>
                    <a href="<?= url("listings/{$listing['id']}") ?>" class="card hover:shadow-lg group">
                        <div class="h-48 bg-gray-200 relative">
                            <?php if (!empty($listing['primary_image'])): ?>
                                <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $listing['primary_image'] ?>"
                                     alt="<?= htmlspecialchars($listing['title']) ?>"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            <?php endif; ?>
                            <?php if ($listing['expat_friendly']): ?>
                                <span class="absolute top-2 right-2 badge-expat">Expat-Friendly</span>
                            <?php endif; ?>
                        </div>
                        <div class="p-4 space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900 line-clamp-2 group-hover:text-primary-600">
                                <?= htmlspecialchars($listing['title']) ?>
                            </h3>
                            <p class="text-sm text-gray-600">
                                <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                            </p>
                            <div class="flex items-center justify-between text-sm text-gray-700">
                                <span class="text-xl font-bold text-primary-600">
                                    €<?= number_format($listing['price'], 0) ?>
                                    <span class="text-xs text-gray-500">/<?= $listing['deal_type'] === 'rent' ? 'mo' : 'total' ?></span>
                                </span>
                                <span><?= $listing['rooms'] ?> rooms • <?= number_format($listing['area_sqm'], 0) ?> m²</span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
