<?php require __DIR__ . '/../layouts/header.php'; ?>

<!-- Hero Section - Full viewport height -->
<section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white"
    style="min-height: calc(100vh - 64px); display: flex; align-items: center;">
    <div class="w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Announcement Badge - Glassmorphism Effect -->
        <div class="text-center mb-10">
            <div class="inline-flex items-center rounded-full px-6 py-3 text-sm font-semibold"
                style="background: rgba(255,255,255,0.25); backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px); border: 1px solid rgba(255,255,255,0.4); box-shadow: 0 8px 32px rgba(0,0,0,0.15);">
                <span style="color: #ffffff; text-shadow: 0 1px 2px rgba(0,0,0,0.2);">
                    ✨ <?= $stats['active'] ?? 0 ?> <?= __('home.active_listings') ?> • 100%
                    <?= __('home.discrimination_free') ?>
                </span>
            </div>
        </div>

        <!-- Main Content -->
        <div class="text-center" style="max-width: 56rem; margin: 0 auto;">
            <!-- Main Title - High Contrast -->
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-8"
                style="line-height: 1.1; color: #ffffff; text-shadow: 0 2px 4px rgba(0,0,0,0.2);">
                <?= __('home.hero_title') ?>
            </h1>

            <!-- Subtitle - Readable -->
            <p class="text-xl sm:text-2xl mb-12"
                style="max-width: 42rem; margin-left: auto; margin-right: auto; line-height: 1.6; color: rgba(255,255,255,0.95);">
                <?= __('home.hero_subtitle') ?>
            </p>

            <!-- Search Bar - Inside Hero with Neon Effect -->
            <div style="max-width: 42rem; margin: 0 auto 3rem auto;">
                <form action="<?= url('listings') ?>" method="GET" class="flex flex-col sm:flex-row neon-search-wrapper"
                    style="box-shadow: 0 10px 40px rgba(0,0,0,0.25), 0 0 30px rgba(59, 130, 246, 0.5); border-radius: 0.75rem; overflow: hidden; animation: neon-pulse 2s ease-in-out infinite;">
                    <input type="text" name="q" placeholder="<?= __('home.search_placeholder') ?>"
                        class="flex-1 px-6 py-4 text-lg text-gray-900"
                        style="border: none; outline: none; background: rgba(255, 255, 255, 0.98);">
                    <button type="submit" class="px-8 py-4 text-lg font-bold neon-button"
                        style="background: linear-gradient(135deg, #3b82f6 0%, #1e3a8a 100%); color: #ffffff; border: none; cursor: pointer; transition: all 0.3s ease; box-shadow: 0 0 20px rgba(59, 130, 246, 0.6);">
                        <span style="display: inline-flex; align-items: center; gap: 0.5rem;">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <?= __('common.search') ?>
                        </span>
                    </button>
                </form>
            </div>

            <style>
                @keyframes neon-pulse {
                    0%, 100% {
                        box-shadow: 0 10px 40px rgba(0,0,0,0.25),
                                    0 0 30px rgba(59, 130, 246, 0.5),
                                    0 0 15px rgba(59, 130, 246, 0.3);
                    }
                    50% {
                        box-shadow: 0 10px 40px rgba(0,0,0,0.25),
                                    0 0 40px rgba(59, 130, 246, 0.7),
                                    0 0 25px rgba(59, 130, 246, 0.5);
                    }
                }

                .neon-button:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 0 30px rgba(59, 130, 246, 0.8),
                                0 5px 20px rgba(0, 0, 0, 0.3) !important;
                }

                .neon-search-wrapper:focus-within {
                    animation: none;
                    box-shadow: 0 10px 40px rgba(0,0,0,0.25),
                                0 0 50px rgba(59, 130, 246, 0.8),
                                0 0 30px rgba(59, 130, 246, 0.6) !important;
                }
            </style>

            <!-- CTA Buttons - High Contrast with proper gap -->
            <div class="flex flex-col sm:flex-row items-center justify-center" style="gap: 2rem;">
                <a href="<?= url('listings') ?>" class="px-8 py-4 text-lg font-bold rounded-lg"
                    style="background: #ffffff; color: #1e3a8a; box-shadow: 0 4px 20px rgba(0,0,0,0.2); transition: transform 0.2s, box-shadow 0.2s;">
                    <?= __('home.view_all') ?>
                </a>
                <a href="<?= url('about') ?>" class="text-lg font-semibold"
                    style="color: #ffffff; border-bottom: 2px solid rgba(255,255,255,0.6); padding-bottom: 4px; transition: border-color 0.2s;">
                    <?= __('home.how_it_works') ?> <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Featured Listings -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900">
                <?= __('home.featured_listings') ?>
            </h2>
            <a href="<?= url('listings') ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('home.view_all') ?> →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($featuredListings as $listing): ?>
                <a href="/listings/<?= $listing['id'] ?>" class="card">
                    <!-- Image -->
                    <div class="h-48 bg-gray-200 overflow-hidden">
                        <?php if (!empty($listing['primary_image'])): ?>
                            <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $listing['primary_image'] ?>"
                                alt="<?= htmlspecialchars($listing['title']) ?>" class="w-full h-full object-cover">
                        <?php endif; ?>
                    </div>

                    <!-- Content -->
                    <div class="p-4">
                        <?php if ($listing['expat_friendly']): ?>
                            <span class="badge-expat mb-2">
                                ✓ Expat-Friendly
                            </span>
                        <?php endif; ?>

                        <h3 class="text-xl font-semibold text-gray-900 mb-2">
                            <?= htmlspecialchars($listing['title']) ?>
                        </h3>

                        <p class="text-gray-600 text-sm mb-3">
                            <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                        </p>

                        <div class="flex justify-between items-center">
                            <div class="text-2xl font-bold text-primary-600">
                                €<?= number_format($listing['price'], 0) ?>/mo
                            </div>
                            <div class="text-sm text-gray-600">
                                <?= $listing['rooms'] ?> rooms • <?= number_format($listing['area_sqm'], 0) ?> m²
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- CTA Banner -->
<section class="bg-primary-600 text-white py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6 text-center md:text-left">
            <div>
                <h3 class="text-2xl font-bold mb-2"><?= __('home.cta_title') ?></h3>
                <p class="text-primary-100"><?= __('home.cta_subtitle') ?></p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="<?= url('listings') ?>"
                    class="px-6 py-3 rounded-lg font-semibold bg-white text-primary-700 hover:bg-gray-100"
                    style="transition: background-color 0.2s;">
                    <?= __('home.view_all') ?>
                </a>
                <a href="<?= url('listings/create') ?>"
                    class="px-6 py-3 rounded-lg font-semibold bg-primary-700 text-white hover:bg-primary-800"
                    style="transition: background-color 0.2s;">
                    + <?= __('common.listings') ?>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="bg-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">
            <?= __('home.how_it_works') ?>
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12" style="max-width: 56rem; margin: 0 auto;">
            <!-- For Renters -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">
                    <?= __('home.for_renters') ?>
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            1</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.browse_listings') ?></p>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            2</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.contact_owners') ?></p>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-primary-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            3</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.find_your_home') ?></p>
                    </div>
                </div>
            </div>

            <!-- For Landlords -->
            <div>
                <h3 class="text-2xl font-semibold text-gray-900 mb-6">
                    <?= __('home.for_landlords') ?>
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-success-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            1</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.list_property') ?></p>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-success-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            2</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.reach_expats') ?></p>
                    </div>
                    <div class="flex items-start">
                        <div
                            class="flex-shrink-0 w-8 h-8 bg-success-600 text-white rounded-full flex items-center justify-center mr-4 font-semibold text-sm">
                            3</div>
                        <p class="text-gray-700" style="padding-top: 0.25rem;"><?= __('home.manage_inquiries') ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>