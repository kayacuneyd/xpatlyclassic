<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('pages.contact.title') ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('pages.contact.lead') ?>
            </p>
        </div>
    </section>


    <!-- Contact Information -->
    <section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="grid md:grid-cols-2 gap-8 mb-12">
            <!-- Get in Touch -->
            <div class="bg-primary-50 p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold mb-4 text-gray-900"><?= __('pages.contact.reach_title') ?></h2>
                <p class="text-gray-700 mb-6 leading-relaxed"><?= __('pages.contact.reach_body') ?></p>

                <div class="space-y-4">
                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-primary-600 mt-1 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900"><?= __('pages.contact.email_label') ?></p>
                            <a href="mailto:hello@xpatly.com"
                                class="text-primary-600 hover:text-primary-700">hello@xpatly.com</a>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-primary-600 mt-1 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z">
                            </path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900"><?= __('pages.contact.phone_label') ?></p>
                            <p class="text-gray-700">+372 0000 0000</p>
                        </div>
                    </div>

                    <div class="flex items-start">
                        <svg class="w-6 h-6 text-primary-600 mt-1 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="font-semibold text-gray-900"><?= __('pages.contact.response_time_label') ?></p>
                            <p class="text-gray-700"><?= __('pages.contact.response_time') ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Info -->
            <div class="bg-gray-50 p-8 rounded-lg shadow-sm">
                <h2 class="text-2xl font-bold mb-4 text-gray-900"><?= __('pages.imprint.company') ?></h2>
                <div class="space-y-3 text-gray-700">
                    <div>
                        <p class="font-semibold text-gray-900"><?= __('pages.imprint.company_name') ?></p>
                        <p class="text-sm text-gray-600">Estonian Company</p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?= __('pages.contact.address_label') ?></p>
                        <p><?= __('pages.imprint.address') ?></p>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900"><?= __('pages.contact.email_label') ?></p>
                        <a href="mailto:hello@xpatly.com"
                            class="text-primary-600 hover:text-primary-700">hello@xpatly.com</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Support Types -->
        <div class="grid md:grid-cols-3 gap-6">
            <!-- General Inquiries -->
            <div
                class="text-center p-6 bg-white border-2 border-gray-200 rounded-lg hover:border-primary-600 transition-colors">
                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2"><?= __('pages.contact.general_inquiries') ?></h3>
                <p class="text-sm text-gray-600"><?= __('pages.contact.general_inquiries_text') ?></p>
            </div>

            <!-- Support -->
            <div
                class="text-center p-6 bg-white border-2 border-gray-200 rounded-lg hover:border-success-600 transition-colors">
                <div class="w-12 h-12 bg-success-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-success-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2"><?= __('pages.contact.support_title') ?></h3>
                <p class="text-sm text-gray-600"><?= __('pages.contact.support_text') ?></p>
            </div>

            <!-- Partnerships -->
            <div
                class="text-center p-6 bg-white border-2 border-gray-200 rounded-lg hover:border-purple-600 transition-colors">
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                </div>
                <h3 class="font-semibold text-gray-900 mb-2"><?= __('pages.contact.partnerships_title') ?></h3>
                <p class="text-sm text-gray-600"><?= __('pages.contact.partnerships_text') ?></p>
            </div>
        </div>
    </section>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>