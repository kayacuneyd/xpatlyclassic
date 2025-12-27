<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <h1 class="text-4xl font-bold mb-4"><?= __('pages.blog.title') ?></h1>
            <p class="text-lg text-primary-100 max-w-3xl"><?= __('pages.blog.lead') ?></p>
        </div>
    </section>

    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold mb-3"><?= __('pages.blog.coming_up') ?></h2>
            <p class="text-gray-700 leading-relaxed"><?= __('pages.blog.body') ?></p>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
