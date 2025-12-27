<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('pages.privacy.title') ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('pages.privacy.lead') ?>
            </p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-3"><?= __('pages.privacy.data_title') ?></h2>
            <p class="text-gray-700 leading-relaxed"><?= __('pages.privacy.data_body') ?></p>
        </div>
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-3"><?= __('pages.privacy.usage_title') ?></h2>
            <p class="text-gray-700 leading-relaxed"><?= __('pages.privacy.usage_body') ?></p>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>