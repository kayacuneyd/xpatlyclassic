<?php require __DIR__ . '/../layouts/header.php'; ?>

<style>
    .auth-guard-active #dashboard-content {
        display: none;
    }

    .auth-guard-active #auth-guard {
        display: flex;
    }
</style>

<div id="auth-guard" class="hidden fixed inset-0 z-50 items-center justify-center bg-white">
    <div class="text-center px-6">
        <div class="mx-auto mb-4 h-10 w-10 animate-spin rounded-full border-4 border-gray-200 border-t-primary-600">
        </div>
        <p id="auth-guard-status" class="text-gray-700 text-sm">Checking your session...</p>
    </div>
</div>

<div id="dashboard-content" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-8">Welcome, <?= htmlspecialchars($user['full_name']) ?>!</h1>

    <!-- New Messages Notification Banner -->
    <?php
    $unreadMessagesCount = \Models\Conversation::getUnreadCount(Core\Auth::id(), 'received');
    if ($unreadMessagesCount > 0):
        ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 mb-8 rounded-lg shadow-sm">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="font-semibold text-blue-900 text-lg">
                            <?= $unreadMessagesCount == 1 ? __('messages.you_have_new_message') ?? 'You have 1 new message' : sprintf(__('messages.you_have_new_messages') ?? 'You have %d new messages', $unreadMessagesCount) ?>
                        </p>
                        <p class="text-sm text-blue-700 mt-1">
                            <?= __('messages.check_inbox') ?? 'Check your inbox to respond to inquiries about your listings' ?>
                        </p>
                    </div>
                </div>
                <a href="<?= url('messages') ?>"
                    class="flex-shrink-0 px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                    <?= __('messages.view_messages') ?? 'View Messages' ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

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
                    <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
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
                    <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
                    </svg>
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
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
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
            <a href="/listings/create"
                class="btn btn-primary text-center inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Listing
            </a>
            <a href="/my-listings" class="btn btn-secondary text-center inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                Manage My Listings
            </a>
            <a href="/listings" class="btn btn-secondary text-center inline-flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                Browse All Listings
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
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm">Verified</span>
                <?php else: ?>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">Pending</span>
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

        <?php if (!$user['email_verified']): ?>
            <div class="mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                <p class="text-sm text-yellow-800">
                    <strong>Verification Required:</strong> You need to verify your email address before you can publish
                    listings.
                </p>
            </div>
        <?php endif; ?>

        <?php if (in_array($user['role'] ?? 'user', ['user', 'owner'], true)): ?>
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">How do you want to use Xpatly?</h3>
                <p class="text-sm text-gray-600 mb-4">You can change this anytime.</p>
                <form method="POST" action="<?= url('user/role') ?>" class="space-y-4">
                    <?= csrf_field() ?>
                    <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary-600">
                        <input type="radio" name="role" value="user" class="mt-1" <?= ($user['role'] ?? 'user') === 'user' ? 'checked' : '' ?>>
                        <div>
                            <p class="font-medium text-gray-900">I am looking for a home</p>
                            <p class="text-sm text-gray-600">Browse and contact property owners.</p>
                        </div>
                    </label>
                    <label class="flex items-start gap-3 p-4 border rounded-lg cursor-pointer hover:border-primary-600">
                        <input type="radio" name="role" value="owner" class="mt-1" <?= ($user['role'] ?? '') === 'owner' ? 'checked' : '' ?>>
                        <div>
                            <p class="font-medium text-gray-900">I want to list a property</p>
                            <p class="text-sm text-gray-600">Create listings and receive inquiries.</p>
                        </div>
                    </label>
                    <button type="submit" class="btn btn-primary">Save preference</button>
                </form>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusEl = document.getElementById('auth-guard-status');
        document.body.classList.add('auth-guard-active');

        fetch('<?= url('me') ?>', { credentials: 'same-origin', cache: 'no-store' })
            .then(function (response) {
                if (!response.ok) {
                    throw new Error('unauthenticated');
                }
                return response.json();
            })
            .then(function (data) {
                if (!data.authenticated) {
                    throw new Error('unauthenticated');
                }
                document.body.classList.remove('auth-guard-active');
            })
            .catch(function () {
                if (statusEl) {
                    statusEl.textContent = 'Login required. Redirecting...';
                }
                window.setTimeout(function () {
                    window.location.href = '<?= url('login') ?>';
                }, 300);
            });
    });
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>