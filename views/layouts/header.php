<!DOCTYPE html>
<html lang="<?= Core\Translation::getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Xpatly - Expat-Friendly Housing' ?></title>
    <meta name="description" content="Find expat-friendly housing in Estonia without discrimination">

    <!-- Tailwind CSS -->
    <link rel="stylesheet" href="/assets/css/app.css?v=<?= filemtime(__DIR__ . '/../../public/assets/css/app.css') ?>">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <?php if (!empty($useMap)): ?>
        <!-- Leaflet.js -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>
</head>

<body class="bg-gray-50">
    <!-- Flash Messages -->
    <?php if (Core\Flash::has()): ?>
        <div class="fixed top-4 right-4 z-50 space-y-2" x-data="{ show: true }" x-show="show"
            x-init="setTimeout(() => show = false, 5000)">
            <?php foreach (Core\Flash::get() as $message): ?>
                <div class="alert alert-<?= $message['type'] ?> max-w-md shadow-lg">
                    <?= htmlspecialchars($message['message']) ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-40" x-data="{ mobileMenuOpen: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="text-2xl font-bold text-primary-600">
                        Xpatly
                    </a>
                </div>

                <!-- Main Navigation - Desktop Only -->
                <div class="hidden md:flex space-x-8">
                    <a href="<?= url('') ?>" class="text-gray-700 hover:text-primary-600"><?= __('common.home') ?></a>
                    <a href="<?= url('listings') ?>"
                        class="text-gray-700 hover:text-primary-600"><?= __('common.listings') ?></a>
                    <a href="<?= url('about') ?>"
                        class="text-gray-700 hover:text-primary-600"><?= __('pages.about.title') ?></a>
                    <a href="<?= url('contact') ?>"
                        class="text-gray-700 hover:text-primary-600"><?= __('pages.contact.title') ?></a>
                </div>

                <!-- Right Side - Desktop & Mobile -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Language Switcher -->
                    <?php
                    $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';
                    // Remove any existing locale prefix to avoid /ru/et/ style URLs
                    $cleanPath = preg_replace('#^/(en|et|ru)#', '', $currentPath);
                    if ($cleanPath === '') {
                        $cleanPath = '/';
                    }
                    ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="text-gray-700 hover:text-primary-600 px-2 sm:px-3 py-2 rounded-md hover:bg-gray-100 flex items-center space-x-1">
                            <span class="text-lg sm:text-xl">
                                <?php
                                $currentLocale = Core\Translation::getLocale();
                                echo $currentLocale === 'en' ? '🇬🇧' : ($currentLocale === 'et' ? '🇪🇪' : '🇷🇺');
                                ?>
                            </span>
                            <span class="hidden sm:inline text-sm"><?= strtoupper($currentLocale) ?></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false"
                            class="absolute right-0 mt-2 w-36 sm:w-40 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                            <a href="/en<?= $cleanPath ?>" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="text-xl mr-2">🇬🇧</span>
                                <span class="hidden sm:inline">English</span>
                            </a>
                            <a href="/et<?= $cleanPath ?>" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="text-xl mr-2">🇪🇪</span>
                                <span class="hidden sm:inline">Eesti</span>
                            </a>
                            <a href="/ru<?= $cleanPath ?>" class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="text-xl mr-2">🇷🇺</span>
                                <span class="hidden sm:inline">Русский</span>
                            </a>
                        </div>
                    </div>

                    <!-- Auth Links -->
                    <?php if (Core\Auth::check()): ?>
                        <!-- Profile Dropdown -->
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-1 text-gray-700 hover:text-primary-600 px-2 py-2 rounded-md hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div x-show="open" x-cloak @click.away="open = false"
                                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <p class="text-sm text-gray-500"><?= __('common.welcome') ?></p>
                                    <p class="text-sm font-medium text-gray-900"><?= e(Core\Auth::user()['email']) ?></p>
                                </div>

                                <a href="<?= url('dashboard') ?>"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                        </path>
                                    </svg>
                                    <?= __('common.dashboard') ?>
                                </a>

                                <?php if (Core\Auth::user()['role'] === 'owner' || Core\Auth::isAdmin()): ?>
                                    <a href="<?= url('my-listings') ?>"
                                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                            </path>
                                        </svg>
                                        <?= __('common.my_listings') ?>
                                    </a>

                                    <a href="<?= url('listings/create') ?>"
                                        class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create Listing
                                    </a>
                                <?php endif; ?>

                                <a href="<?= url('favorites') ?>"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                        </path>
                                    </svg>
                                    <?= __('common.favorites') ?>
                                </a>

                                <?php if (Core\Auth::isAdmin()): ?>
                                    <div class="border-t border-gray-200 my-1"></div>
                                    <a href="<?= url('admin') ?>"
                                        class="flex items-center px-4 py-2 text-purple-700 hover:bg-purple-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z">
                                            </path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <?= __('common.admin') ?>
                                    </a>
                                <?php endif; ?>

                                <div class="border-t border-gray-200 my-1"></div>
                                <form method="POST" action="<?= url('logout') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit"
                                        class="flex items-center w-full px-4 py-2 text-left text-red-700 hover:bg-red-50">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                            </path>
                                        </svg>
                                        <?= __('common.logout') ?>
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="<?= url('login') ?>" class="hidden md:inline-block btn btn-secondary text-sm px-4 py-2"><?= __('common.login') ?></a>
                        <a href="<?= url('register') ?>" class="hidden md:inline-block btn btn-primary text-sm px-4 py-2"><?= __('common.register') ?></a>
                    <?php endif; ?>

                    <!-- Hamburger Menu Button - Mobile Only -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                            class="md:hidden p-2 rounded-md text-gray-700 hover:text-primary-600 hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 -translate-y-1"
                 x-transition:enter-end="opacity-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0"
                 x-transition:leave-end="opacity-0 -translate-y-1"
                 class="md:hidden border-t border-gray-200 bg-white">
                <div class="px-4 py-4 space-y-3">
                    <!-- Main Navigation Links -->
                    <a href="<?= url('') ?>" class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('common.home') ?>
                    </a>
                    <a href="<?= url('listings') ?>" class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('common.listings') ?>
                    </a>
                    <a href="<?= url('about') ?>" class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('pages.about.title') ?>
                    </a>
                    <a href="<?= url('contact') ?>" class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('pages.contact.title') ?>
                    </a>

                    <?php if (!Core\Auth::check()): ?>
                        <!-- Auth Links for Non-logged Users -->
                        <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                            <a href="<?= url('login') ?>" class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                <?= __('common.login') ?>
                            </a>
                            <a href="<?= url('register') ?>" class="block w-full text-center py-2 px-4 bg-primary-600 text-white rounded-md hover:bg-primary-700">
                                <?= __('common.register') ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>