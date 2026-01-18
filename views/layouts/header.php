<!DOCTYPE html>
<html lang="<?= Core\Translation::getLocale() ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Xpatly - Expat-Friendly Housing' ?></title>
    <meta name="description" content="<?= $metaDescription ?? 'Find expat-friendly housing in Estonia without discrimination' ?>">

    <?php $ga4Id = trim((string) settings('ga4_measurement_id', '')); ?>
    <?php if ($ga4Id !== ''): ?>
        <script async src="https://www.googletagmanager.com/gtag/js?id=<?= htmlspecialchars($ga4Id) ?>"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());
            gtag('config', '<?= htmlspecialchars($ga4Id) ?>');
        </script>
    <?php endif; ?>

    <!-- SEO Meta Tags -->
    <meta name="robots" content="index, follow">
    <meta name="googlebot" content="index, follow">
    <link rel="canonical" href="https://xpatly.eu<?= $_SERVER['REQUEST_URI'] ?? '/' ?>">

    <!-- Multilingual Alternate Links -->
    <link rel="alternate" hreflang="en" href="https://xpatly.eu/en<?= preg_replace('/^\/(en|et|ru)/', '', $_SERVER['REQUEST_URI'] ?? '/') ?>">
    <link rel="alternate" hreflang="et" href="https://xpatly.eu/et<?= preg_replace('/^\/(en|et|ru)/', '', $_SERVER['REQUEST_URI'] ?? '/') ?>">
    <link rel="alternate" hreflang="ru" href="https://xpatly.eu/ru<?= preg_replace('/^\/(en|et|ru)/', '', $_SERVER['REQUEST_URI'] ?? '/') ?>">
    <link rel="alternate" hreflang="x-default" href="https://xpatly.eu/en<?= preg_replace('/^\/(en|et|ru)/', '', $_SERVER['REQUEST_URI'] ?? '/') ?>">

    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#4F46E5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Xpatly">

    <!-- PWA Icons -->
    <?php $siteIcon = settings('site_icon'); ?>
    <link rel="manifest" href="/manifest.json">
    <?php if (!empty($siteIcon)): ?>
        <link rel="icon" href="<?= $siteIcon ?>">
        <link rel="apple-touch-icon" href="<?= $siteIcon ?>">
    <?php else: ?>
        <link rel="icon" href="/favicon.ico" type="image/x-icon">
        <link rel="apple-touch-icon" href="/assets/images/icon-192.png">
        <link rel="icon" type="image/png" sizes="192x192" href="/assets/images/icon-192.png">
        <link rel="icon" type="image/png" sizes="512x512" href="/assets/images/icon-512.png">
    <?php endif; ?>

    <!-- Open Graph for Social Sharing -->
    <meta property="og:title" content="<?= $title ?? 'Xpatly - Housing in Estonia' ?>">
    <meta property="og:description" content="<?= $metaDescription ?? 'Find your perfect home in Estonia. Housing platform for expats and locals.' ?>">
    <meta property="og:image" content="<?= $ogImage ?? url('assets/images/og-image.jpg') ?>">
    <meta property="og:url" content="<?= url($_SERVER['REQUEST_URI'] ?? '/') ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= $title ?? 'Xpatly - Housing in Estonia' ?>">
    <meta name="twitter:description" content="<?= $metaDescription ?? 'Find your perfect home in Estonia.' ?>">
    <meta name="twitter:image" content="<?= $ogImage ?? url('assets/images/og-image.jpg') ?>">

    <!-- Google Fonts - Lexend & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Lexend:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- DNS Prefetch for CDNs (faster initial connection) -->
    <link rel="dns-prefetch" href="https://cdn.tailwindcss.com">
    <link rel="dns-prefetch" href="https://cdn.jsdelivr.net">
    <link rel="dns-prefetch" href="https://unpkg.com">

    <!-- TailwindCSS CDN with Custom Config -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        // Suppress production warning (guard for offline/blocked CDN)
        if (window.tailwind) {
            tailwind.config = {
                disableDevWarnings: true,
                theme: {
                    extend: {
                        colors: {
                            primary: {
                                50: '#fffde7',
                                100: '#fff9c4',
                                200: '#fff59d',
                                300: '#fff176',
                                400: '#ffee58',
                                500: '#f9a825',
                                600: '#f9a825',
                                700: '#f57f17',
                                800: '#e65100',
                                900: '#bf360c',
                            },
                            secondary: {
                                50: '#e3f2fd',
                                100: '#bbdefb',
                                200: '#90caf9',
                                300: '#64b5f6',
                                400: '#42a5f5',
                                500: '#1976d2',
                                600: '#1976d2',
                                700: '#1565c0',
                                800: '#0d47a1',
                                900: '#0a3d91',
                            },
                            success: {
                                500: '#22c55e',
                                600: '#16a34a',
                                700: '#15803d',
                            },
                        },
                        fontFamily: {
                            'heading': ['Lexend', 'sans-serif'],
                            'body': ['Inter', 'sans-serif'],
                        },
                    },
                },
            };
        }
    </script>

    <!-- Custom Styles -->
    <link rel="stylesheet" href="/assets/css/custom.css">

    <!-- Alpine.js (load only when needed) -->
    <script>
    (function() {
        function needsAlpine() {
            return document.querySelector('[x-data],[x-show],[x-cloak],[x-transition],[x-collapse]') !== null;
        }
        function loadScript(src) {
            return new Promise(function(resolve, reject) {
                var script = document.createElement('script');
                script.src = src;
                script.defer = true;
                script.onload = resolve;
                script.onerror = reject;
                document.head.appendChild(script);
            });
        }
        function init() {
            if (needsAlpine()) {
                loadScript('https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js')
                    .then(function() {
                        return loadScript('https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js');
                    })
                    .catch(function() {});
            }
        }
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>

    <?php if (!empty($useMap)): ?>
        <!-- Leaflet.js -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <?php endif; ?>

    <!-- CSRF Token Auto-Sync -->
    <script>
    // Auto-refresh CSRF tokens before form submission to prevent mismatches
    document.addEventListener('DOMContentLoaded', function() {
        // Sync CSRF token from cookie to all forms
        function syncCsrfToken() {
            const cookieToken = getCookie('csrf_token');
            if (cookieToken) {
                document.querySelectorAll('input[name="_token"]').forEach(function(input) {
                    if (input.value !== cookieToken) {
                        input.value = cookieToken;
                    }
                });
            }
        }

        function getCookie(name) {
            const value = `; ${document.cookie}`;
            const parts = value.split(`; ${name}=`);
            if (parts.length === 2) return parts.pop().split(';').shift();
        }

        // Inject honeypot field into all POST forms
        const honeypotName = '<?= addslashes(Core\Session::getHoneypotFieldName()) ?>';
        document.querySelectorAll('form').forEach(function(form) {
            const method = (form.getAttribute('method') || '').toLowerCase();
            if (method !== 'post') {
                return;
            }
            if (form.querySelector(`input[name="${honeypotName}"]`)) {
                return;
            }
            const hp = document.createElement('input');
            hp.type = 'text';
            hp.name = honeypotName;
            hp.autocomplete = 'off';
            hp.tabIndex = '-1';
            hp.style.position = 'absolute';
            hp.style.left = '-9999px';
            hp.style.width = '1px';
            hp.style.height = '1px';
            form.appendChild(hp);
        });

        // Sync on page load
        syncCsrfToken();

        // Sync before every form submission
        document.querySelectorAll('form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                syncCsrfToken();
            });
        });

        // Sync periodically (every 5 seconds) to handle session regeneration
        setInterval(syncCsrfToken, 5000);
    });
    </script>
</head>

<body class="bg-gray-50 font-body">
    <!-- Toast Notification System - Centered Modal Style -->
    <?php $flashMessages = Core\Flash::get(); ?>
    <?php if (!empty($flashMessages)): ?>
        <div class="fixed inset-0 z-50 flex items-start justify-center pt-20 pointer-events-none" style="z-index: 10000;">
            <?php foreach ($flashMessages as $index => $message): ?>
                <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform -translate-y-4"
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform translate-y-0"
                    x-transition:leave-end="opacity-0 transform -translate-y-4" class="pointer-events-auto max-w-md w-full mx-4 mb-3 <?php
                                                                                                                                        echo match ($message['type']) {
                                                                                                                                            'success' => 'bg-green-50 border-green-400 text-green-800',
                                                                                                                                            'error' => 'bg-red-50 border-red-400 text-red-800',
                                                                                                                                            'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
                                                                                                                                            default => 'bg-blue-50 border-blue-400 text-blue-800',
                                                                                                                                        };
                                                                                                                                        ?> border-l-4 rounded-lg shadow-xl p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <?php if ($message['type'] === 'success'): ?>
                                <svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            <?php elseif ($message['type'] === 'error'): ?>
                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                        clip-rule="evenodd" />
                                </svg>
                            <?php elseif ($message['type'] === 'warning'): ?>
                                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                        clip-rule="evenodd" />
                                </svg>
                            <?php else: ?>
                                <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                        clip-rule="evenodd" />
                                </svg>
                            <?php endif; ?>
                        </div>
                        <div class="ml-3 flex-1">
                            <p class="text-sm font-medium"><?= htmlspecialchars($message['message']) ?></p>
                        </div>
                        <button @click="show = false" class="ml-4 flex-shrink-0 text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- Console Logging for Auth Errors & Validation -->
    <script>
    // Log validation errors to console for debugging
    <?php if (Core\Session::has('validation_errors')): ?>
        console.group('%c🚫 Validation Errors', 'color: #ef4444; font-weight: bold; font-size: 14px');
        <?php
        $errors = json_decode(Core\Session::get('validation_errors'), true);
        if ($errors && is_array($errors)):
            foreach ($errors as $field => $error):
        ?>
        <?php $errorText = is_array($error) ? implode(', ', $error) : (string) $error; ?>
        console.error(<?= json_encode((string) $field) ?>, <?= json_encode($errorText) ?>);
        <?php
            endforeach;
        endif;
        ?>
        console.groupEnd();
        <?php Core\Session::remove('validation_errors'); ?>
    <?php endif; ?>

    // Log auth errors
    <?php if (!empty($flashMessages)): ?>
        <?php foreach ($flashMessages as $flashMessage): ?>
            <?php if (($flashMessage['type'] ?? '') === 'error'): ?>
                console.error('%c⚠️ Error:', 'color: #ef4444; font-weight: bold', <?= json_encode((string) ($flashMessage['message'] ?? '')) ?>);
            <?php elseif (($flashMessage['type'] ?? '') === 'success'): ?>
                console.log('%c✅ Success:', 'color: #10b981; font-weight: bold', <?= json_encode((string) ($flashMessage['message'] ?? '')) ?>);
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    </script>

    <!-- Email Verification Warning Banner -->
    <?php if (Core\Auth::check() && !\Models\User::isEmailVerified(Core\Auth::id())): ?>
        <div class="bg-amber-500 text-amber-900 py-3 px-4" x-data="{ show: true }" x-show="show">
            <div class="max-w-7xl mx-auto flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd" />
                    </svg>
                    <span class="text-sm font-medium">
                        <?= __('auth.verify_email_warning') ?? 'Please verify your email address to access all features.' ?>
                    </span>
                    <a href="<?= url('resend-verification') ?>" class="ml-3 text-sm font-bold underline hover:no-underline">
                        <?= __('auth.resend_verification') ?? 'Resend verification email' ?>
                    </a>
                </div>
                <button @click="show = false" class="text-amber-900 hover:text-amber-700" aria-label="Dismiss">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    <?php endif; ?>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" class="hidden bg-indigo-600 text-white py-3 px-4 shadow-lg" style="z-index: 9999;">
        <div class="max-w-7xl mx-auto flex flex-col sm:flex-row items-center justify-between gap-3">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                </svg>
                <p class="text-sm font-medium">
                    <?= __('pwa.install_prompt') ?? 'Install Xpatly app for quick access and offline use!' ?>
                </p>
            </div>
            <div class="flex gap-2">
                <button id="pwa-install-btn" class="px-4 py-2 bg-white text-indigo-600 rounded-lg text-sm font-semibold hover:bg-gray-100 transition-colors">
                    <?= __('pwa.install') ?? 'Install' ?>
                </button>
                <button id="pwa-dismiss-btn" class="px-4 py-2 bg-indigo-700 text-white rounded-lg text-sm font-semibold hover:bg-indigo-800 transition-colors">
                    <?= __('pwa.later') ?? 'Later' ?>
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-md sticky top-0 z-40" x-data="{ mobileMenuOpen: false }" style="z-index: 2000;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <a href="/" class="flex items-center">
                        <?php if (!empty(settings('site_logo'))): ?>
                            <img src="<?= settings('site_logo') ?>"
                                 alt="<?= settings('site_name', 'Xpatly') ?>"
                                 class="h-12 sm:h-14 w-auto"
                                 width="112"
                                 height="56">
                        <?php else: ?>
                            <span class="text-2xl font-bold text-primary-600">
                                <?= settings('site_name', 'Xpatly') ?>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>
                <!-- Main Navigation - Desktop Onlysssss -->
                <!-- Main Navigation - Desktop Only -->
                <!-- Main Navigation - Desktop Only -->
                <div class="hidden md:flex space-x-8">
                    <a href="<?= url('') ?>" class="text-gray-700 hover:text-primary-600"><?= __('common.home') ?></a>
                    <a href="<?= url('listings') ?>"
                        class="text-gray-700 hover:text-primary-600"><?= __('common.listings') ?></a>
                    <a href="<?= url('blog') ?>"
                        class="text-gray-700 hover:text-primary-600"><?= __('common.blog') ?? 'Blog' ?></a>
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
                    // Hem /public/ hem de dil ön ekini kaldır
                    $cleanPath = preg_replace('#^/(?:public/)?(?:en|et|ru)?#', '', $currentPath);
                    if ($cleanPath === '' || $cleanPath === false) {
                        $cleanPath = '/';
                    }
                    ?>
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="text-gray-700 hover:text-primary-600 px-2 sm:px-3 py-2 rounded-md hover:bg-gray-100 flex items-center space-x-1"
                            aria-label="Select language"
                            :aria-expanded="open.toString()">
                            <span class="flag-icon <?php
                                                    $currentLocale = Core\Translation::getLocale();
                                                    echo $currentLocale === 'en' ? 'flag-icon-gb' : ($currentLocale === 'et' ? 'flag-icon-ee' : 'flag-icon-ru');
                                                    ?>"></span>
                            <span class="hidden sm:inline text-sm ml-1"><?= strtoupper($currentLocale) ?></span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                        <div x-show="open" x-cloak @click.away="open = false"
                            class="absolute right-0 mt-2 w-36 sm:w-40 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                            <a href="/en<?= $cleanPath ?>"
                                class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="flag-icon flag-icon-gb mr-2"></span>
                                <span>English</span>
                            </a>
                            <a href="/et<?= $cleanPath ?>"
                                class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="flag-icon flag-icon-ee mr-2"></span>
                                <span>Eesti</span>
                            </a>
                            <a href="/ru<?= $cleanPath ?>"
                                class="flex items-center px-3 py-2 text-gray-700 hover:bg-gray-100">
                                <span class="flag-icon flag-icon-ru mr-2"></span>
                                <span>Русский</span>
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

                                <a href="<?= url('messages') ?>"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100 relative">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                                        </path>
                                    </svg>
                                    <?= __('common.messages') ?? 'Messages' ?>
                                    <?php
                                    $unreadMessages = \Models\Conversation::getUnreadCount(Core\Auth::id(), 'received') +
                                        \Models\Conversation::getUnreadCount(Core\Auth::id(), 'sent');
                                    if ($unreadMessages > 0):
                                    ?>
                                        <span class="ml-auto bg-blue-500 text-white text-xs font-semibold px-2 py-0.5 rounded-full">
                                            <?= $unreadMessages ?>
                                        </span>
                                    <?php endif; ?>
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
                        <!-- Combined Sign In / Register Dropdown -->
                        <div x-data="{ open: false }" class="relative hidden md:block">
                            <button @click="open = !open"
                                class="flex items-center space-x-1 bg-secondary-800 hover:bg-secondary-900 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span><?= __('common.login') ?></span>
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div x-show="open" x-cloak @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 border border-gray-200 z-50">
                                <a href="<?= url('login') ?>"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                                        </path>
                                    </svg>
                                    <?= __('common.login') ?>
                                </a>
                                <a href="<?= url('register') ?>"
                                    class="flex items-center px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z">
                                        </path>
                                    </svg>
                                    <?= __('common.register') ?>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Hamburger Menu Button - Mobile Only -->
                    <button @click="mobileMenuOpen = !mobileMenuOpen"
                        class="md:hidden p-2 rounded-md text-gray-700 hover:text-primary-600 hover:bg-gray-100"
                        aria-label="Toggle navigation menu">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path x-show="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            <path x-show="mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="mobileMenuOpen" x-cloak x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-1"
                class="md:hidden border-t border-gray-200 bg-white">
                <div class="px-4 py-4 space-y-3">
                    <!-- Main Navigation Links -->
                    <a href="<?= url('') ?>"
                        class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('common.home') ?>
                    </a>
                    <a href="<?= url('listings') ?>"
                        class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('common.listings') ?>
                    </a>
                    <a href="<?= url('about') ?>"
                        class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('pages.about.title') ?>
                    </a>
                    <a href="<?= url('contact') ?>"
                        class="block py-2 text-gray-700 hover:text-primary-600 hover:bg-gray-50 rounded-md px-3">
                        <?= __('pages.contact.title') ?>
                    </a>

                    <?php if (!Core\Auth::check()): ?>
                        <!-- Auth Links for Non-logged Users -->
                        <div class="border-t border-gray-200 pt-3 mt-3 space-y-2">
                            <a href="<?= url('login') ?>"
                                class="block w-full text-center py-2 px-4 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50">
                                <?= __('common.login') ?>
                            </a>
                            <a href="<?= url('register') ?>"
                                class="block w-full text-center py-2 px-4 bg-primary-600 text-white rounded-md hover:bg-primary-700">
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
