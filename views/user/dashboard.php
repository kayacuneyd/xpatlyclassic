<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome, <?= htmlspecialchars($user['full_name']) ?>!</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- My Listings -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">My Listings</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $listingsCount ?></p>
                </div>
                <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">🏠</span>
                </div>
            </div>
            <a href="/my-listings" class="text-primary-600 text-sm hover:underline mt-4 inline-block">
                View all listings →
            </a>
        </div>

        <!-- Favorites -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Saved Favorites</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $favoritesCount ?></p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">❤️</span>
                </div>
            </div>
            <a href="/favorites" class="text-primary-600 text-sm hover:underline mt-4 inline-block">
                View favorites →
            </a>
        </div>

        <!-- Messages -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm">Unread Messages</p>
                    <p class="text-3xl font-bold text-gray-900"><?= $unreadMessages ?></p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <span class="text-2xl">✉️</span>
                </div>
            </div>
            <a href="/messages" class="text-primary-600 text-sm hover:underline mt-4 inline-block">
                View messages →
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <a href="/listings/create" class="btn btn-primary text-center">
                ➕ Create New Listing
            </a>
            <a href="/my-listings" class="btn btn-secondary text-center">
                📋 Manage My Listings
            </a>
            <a href="/listings" class="btn btn-secondary text-center">
                🔍 Browse All Listings
            </a>
        </div>
    </div>

    <!-- Account Status -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-semibold mb-4">Account Status</h2>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-gray-600">Email Verification</span>
                <?php if ($user['email_verified']): ?>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">✓ Verified</span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">⚠ Pending</span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-gray-600">Phone Verification</span>
                <?php if ($user['phone_verified']): ?>
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">✓ Verified</span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">⚠ Pending</span>
                <?php endif; ?>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-gray-600">Account Type</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                    <?= ucfirst($user['role']) ?>
                </span>
            </div>

            <div class="flex items-center justify-between">
                <span class="text-gray-600">Member Since</span>
                <span class="text-gray-700"><?= date('F j, Y', strtotime($user['created_at'])) ?></span>
            </div>
        </div>

        <?php if (!$user['email_verified'] || !$user['phone_verified']): ?>
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    ⚠️ <strong>Verification Required:</strong> You need to verify both your email and phone number before you can publish listings.
                </p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
