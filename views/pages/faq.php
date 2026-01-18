<?php
$pageTitle = __('pages.faq.title') ?? 'FAQ';
$metaDescription = __('pages.faq.lead') ?? 'Frequently asked questions about Xpatly.';
require __DIR__ . '/../layouts/header.php';
?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('pages.faq.title') ?? 'FAQ' ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('pages.faq.lead') ?? 'Frequently asked questions about Xpatly.' ?>
            </p>
        </div>
    </section>

    <!-- FAQ Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16">

        <!-- FAQ Categories with Alpine.js Accordion -->
        <div x-data="{ openCategory: null, openQuestion: null }">

            <!-- Getting Started -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </span>
                    <?= __('pages.faq.category.getting_started') ?? 'Getting Started' ?>
                </h2>

                <div class="space-y-3">
                    <!-- Question 1 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 1 ? null : 1"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.getting_started.q1') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 1 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 1" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.getting_started.a1') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 2 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 2 ? null : 2"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.getting_started.q2') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 2 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 2" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.getting_started.a2') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 3 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 3 ? null : 3"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.getting_started.q3') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 3 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 3" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.getting_started.a3') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Tenants -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-blue-100 text-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </span>
                    <?= __('pages.faq.category.for_tenants') ?? 'For Tenants' ?>
                </h2>

                <div class="space-y-3">
                    <!-- Question 4 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 4 ? null : 4"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_tenants.q1') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 4 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 4" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_tenants.a1') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 5 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 5 ? null : 5"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_tenants.q2') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 5 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 5" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_tenants.a2') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 6 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 6 ? null : 6"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_tenants.q3') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 6 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 6" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_tenants.a3') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 7 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 7 ? null : 7"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_tenants.q4') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 7 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 7" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_tenants.a4') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- For Landlords -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-orange-100 text-orange-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </span>
                    <?= __('pages.faq.category.for_landlords') ?? 'For Landlords' ?>
                </h2>

                <div class="space-y-3">
                    <!-- Question 8 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 8 ? null : 8"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_landlords.q1') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 8 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 8" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_landlords.a1') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 9 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 9 ? null : 9"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_landlords.q2') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 9 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 9" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_landlords.a2') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 10 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 10 ? null : 10"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_landlords.q3') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 10 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 10" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_landlords.a3') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 11 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 11 ? null : 11"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.for_landlords.q4') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 11 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 11" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.for_landlords.a4') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payments & Safety -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </span>
                    <?= __('pages.faq.category.payments_safety') ?? 'Payments & Safety' ?>
                </h2>

                <div class="space-y-3">
                    <!-- Question 12 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 12 ? null : 12"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.payments_safety.q1') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 12 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 12" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.payments_safety.a1') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 13 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 13 ? null : 13"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.payments_safety.q2') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 13 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 13" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.payments_safety.a2') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 14 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 14 ? null : 14"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.payments_safety.q3') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 14 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 14" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.payments_safety.a3') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Technical Support -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                    <span class="w-10 h-10 bg-purple-100 text-purple-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </span>
                    <?= __('pages.faq.category.technical') ?? 'Technical Support' ?>
                </h2>

                <div class="space-y-3">
                    <!-- Question 15 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 15 ? null : 15"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.technical.q1') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 15 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 15" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.technical.a1') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 16 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 16 ? null : 16"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.technical.q2') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 16 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 16" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.technical.a2') ?>
                            </div>
                        </div>
                    </div>

                    <!-- Question 17 -->
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <button @click="openQuestion = openQuestion === 17 ? null : 17"
                                class="w-full px-6 py-4 text-left flex items-center justify-between hover:bg-gray-50 transition-colors">
                            <span class="font-semibold text-gray-900"><?= __('pages.faq.technical.q3') ?></span>
                            <svg class="w-5 h-5 text-gray-400 transition-transform" :class="{ 'rotate-180': openQuestion === 17 }"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="openQuestion === 17" x-collapse>
                            <div class="px-6 pb-4 text-gray-600">
                                <?= __('pages.faq.technical.a3') ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- Still Have Questions CTA -->
        <div class="mt-12 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl p-8 text-center text-white">
            <h3 class="text-2xl font-bold mb-3">
                <?= __('pages.faq.still_questions') ?? 'Still have questions?' ?>
            </h3>
            <p class="text-indigo-100 mb-6">
                <?= __('pages.faq.contact_us') ?? 'Our team is here to help. Get in touch and we\'ll respond as soon as possible.' ?>
            </p>
            <a href="<?= url('contact') ?>" class="inline-flex items-center gap-2 px-6 py-3 bg-white text-indigo-600 font-semibold rounded-lg hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                <?= __('pages.faq.contact_button') ?? 'Contact Support' ?>
            </a>
        </div>

    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
