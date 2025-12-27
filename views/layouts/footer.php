</main>

<!-- Footer -->
<footer class="bg-gray-800 text-white mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- About -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Xpatly</h3>
                <p class="text-gray-400 text-sm">
                    Expat-friendly real estate platform for Estonia. Find your perfect home without discrimination.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= url('') ?>" class="text-gray-400 hover:text-white">Home</a></li>
                    <li><a href="<?= url('listings') ?>" class="text-gray-400 hover:text-white">Browse Listings</a></li>
                    <li><a href="<?= url('listings/create') ?>" class="text-gray-400 hover:text-white">List Property</a>
                    </li>
                </ul>
            </div>

            <!-- For Users -->
            <div>
                <h3 class="text-lg font-semibold mb-4">For Users</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="<?= url('register') ?>" class="text-gray-400 hover:text-white">Register</a></li>
                    <li><a href="<?= url('login') ?>" class="text-gray-400 hover:text-white">Login</a></li>
                    <li><a href="<?= url('dashboard') ?>" class="text-gray-400 hover:text-white">Dashboard</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h3 class="text-lg font-semibold mb-4">Legal</h3>
                <ul class="space-y-2 text-sm">
                    <li>
                        <a href="<?= url('imprint') ?>" class="text-gray-400 hover:text-white">
                            <?= __('pages.imprint.title') ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?= url('privacy') ?>" class="text-gray-400 hover:text-white">
                            <?= __('pages.privacy.title') ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-gray-700 mt-8 pt-8 text-center text-sm text-gray-400">
            <p>&copy; <?= date('Y') ?> Xpatly. All rights reserved.</p>
            <p class="mt-2">Developed by <a href="https://kayacuneyt.com" target="_blank"
                    class="text-primary-400 hover:text-primary-300">Cüneyt Kaya</a></p>
        </div>
    </div>
</footer>
</body>

</html>