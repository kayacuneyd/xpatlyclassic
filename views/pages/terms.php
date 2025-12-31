<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('pages.terms.title') ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('pages.terms.lead') ?>
            </p>
            <p class="text-sm text-primary-200 mt-4">
                <?= __('pages.terms.last_updated') ?>: <?= __('pages.terms.last_updated_date') ?>
            </p>
        </div>
    </section>

    <!-- Terms Content -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-6">

        <!-- 1. Acceptance of Terms -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">1. <?= __('pages.terms.acceptance_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.acceptance_body') ?></p>
        </div>

        <!-- 2. User Accounts -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">2. <?= __('pages.terms.accounts_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.accounts_body') ?></p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li><?= __('pages.terms.accounts_point1') ?></li>
                <li><?= __('pages.terms.accounts_point2') ?></li>
                <li><?= __('pages.terms.accounts_point3') ?></li>
                <li><?= __('pages.terms.accounts_point4') ?></li>
            </ul>
        </div>

        <!-- 3. Listings and Content -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">3. <?= __('pages.terms.listings_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.listings_body') ?></p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li><?= __('pages.terms.listings_point1') ?></li>
                <li><?= __('pages.terms.listings_point2') ?></li>
                <li><?= __('pages.terms.listings_point3') ?></li>
                <li><?= __('pages.terms.listings_point4') ?></li>
                <li><?= __('pages.terms.listings_point5') ?></li>
            </ul>
        </div>

        <!-- 4. Prohibited Activities -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">4. <?= __('pages.terms.prohibited_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.prohibited_body') ?></p>
            <ul class="list-disc list-inside text-gray-700 space-y-2 ml-4">
                <li><?= __('pages.terms.prohibited_point1') ?></li>
                <li><?= __('pages.terms.prohibited_point2') ?></li>
                <li><?= __('pages.terms.prohibited_point3') ?></li>
                <li><?= __('pages.terms.prohibited_point4') ?></li>
                <li><?= __('pages.terms.prohibited_point5') ?></li>
                <li><?= __('pages.terms.prohibited_point6') ?></li>
            </ul>
        </div>

        <!-- 5. Verification and Moderation -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">5. <?= __('pages.terms.moderation_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.moderation_body') ?></p>
        </div>

        <!-- 6. Intellectual Property -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">6. <?= __('pages.terms.ip_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.ip_body') ?></p>
        </div>

        <!-- 7. Liability and Disclaimers -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">7. <?= __('pages.terms.liability_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.liability_body') ?></p>
            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 mt-4">
                <p class="text-sm text-yellow-800 font-medium"><?= __('pages.terms.liability_disclaimer') ?></p>
            </div>
        </div>

        <!-- 8. User Disputes -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">8. <?= __('pages.terms.disputes_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.disputes_body') ?></p>
        </div>

        <!-- 9. Termination -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">9. <?= __('pages.terms.termination_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.termination_body') ?></p>
        </div>

        <!-- 10. Changes to Terms -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">10. <?= __('pages.terms.changes_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.changes_body') ?></p>
        </div>

        <!-- 11. Governing Law -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">11. <?= __('pages.terms.law_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.law_body') ?></p>
        </div>

        <!-- 12. Contact -->
        <div class="bg-gray-50 rounded-lg shadow-sm p-6">
            <h2 class="text-2xl font-semibold mb-4 text-primary-700">12. <?= __('pages.terms.contact_title') ?></h2>
            <p class="text-gray-700 leading-relaxed mb-3"><?= __('pages.terms.contact_body') ?></p>
            <p class="text-gray-700">
                <strong><?= __('common.email') ?>:</strong>
                <a href="mailto:<?= settings('contact_email', 'hello@xpatly.com') ?>" class="text-primary-600 hover:underline">
                    <?= settings('contact_email', 'hello@xpatly.com') ?>
                </a>
            </p>
        </div>

    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
