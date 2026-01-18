<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('pages.about.title') ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('pages.about.lead') ?>
            </p>
        </div>
    </section>


    <!-- Our Story -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4"><?= __('pages.about.story_title') ?></h2>
            <p class="text-lg text-gray-700 leading-relaxed max-w-3xl mx-auto">
                <?= __('pages.about.story_body') ?>
            </p>
        </div>
    </section>

    <!-- Why Choose Xpatly -->
    <section class="bg-gray-50 py-16">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center text-gray-900 mb-12"><?= __('pages.about.why_title') ?></h2>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- No Discrimination -->
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900"><?= __('pages.about.why_no_discrimination') ?>
                    </h3>
                    <p class="text-gray-600 leading-relaxed"><?= __('pages.about.why_no_discrimination_text') ?></p>
                </div>

                <!-- Verified Listings -->
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900"><?= __('pages.about.why_verified') ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?= __('pages.about.why_verified_text') ?></p>
                </div>

                <!-- Direct Contact -->
                <div class="bg-white p-8 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mb-6">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold mb-3 text-gray-900"><?= __('pages.about.why_direct') ?></h3>
                    <p class="text-gray-600 leading-relaxed"><?= __('pages.about.why_direct_text') ?></p>
                </div>
            </div>
        </div>
    </section>

    <!-- Mission & Values -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-8">
            <!-- Mission -->
            <div class="bg-primary-50 p-8 rounded-lg border-l-4 border-primary-600">
                <h2 class="text-2xl font-bold mb-4 text-gray-900"><?= __('pages.about.mission_title') ?></h2>
                <p class="text-gray-700 leading-relaxed text-lg"><?= __('pages.about.mission_body') ?></p>
            </div>

            <!-- Values -->
            <div class="bg-success-50 p-8 rounded-lg border-l-4 border-success-600">
                <h2 class="text-2xl font-bold mb-4 text-gray-900"><?= __('pages.about.values_title') ?></h2>
                <p class="text-gray-700 leading-relaxed text-lg"><?= __('pages.about.values_body') ?></p>
            </div>
        </div>
    </section>

    <!-- Our Commitment -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-16">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold mb-6"><?= __('pages.about.commitment_title') ?></h2>
            <p class="text-xl text-primary-100 leading-relaxed max-w-3xl mx-auto mb-8">
                <?= __('pages.about.commitment_body') ?>
            </p>
            <a href="<?= url('listings') ?>"
                class="btn btn-secondary bg-white text-primary-700 hover:text-primary-800 inline-flex items-center">
                <?= __('home.view_all') ?>
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </a>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>