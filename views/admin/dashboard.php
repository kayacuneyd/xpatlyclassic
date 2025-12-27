<?php
use Core\Auth;

Auth::requireRole(['super_admin', 'moderator']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900"><?= __('admin.dashboard') ?></h1>
            <p class="mt-2 text-gray-600">Welcome back, <?= e(Auth::user()['full_name']) ?></p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Listings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?= __('admin.total_listings') ?>
                            </dt>
                            <dd class="text-3xl font-semibold text-gray-900">
                                <?= $stats['total_listings'] ?? 0 ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Active Listings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?= __('admin.active_listings') ?>
                            </dt>
                            <dd class="text-3xl font-semibold text-gray-900">
                                <?= $stats['active_listings'] ?? 0 ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Pending Listings -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?= __('admin.pending_listings') ?>
                            </dt>
                            <dd class="text-3xl font-semibold text-gray-900">
                                <?= $pendingListings ?? 0 ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">
                                <?= __('admin.total_users') ?>
                            </dt>
                            <dd class="text-3xl font-semibold text-gray-900">
                                <?= $stats['total_users'] ?? 0 ?>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Manage Listings -->
            <a href="<?= url('admin/listings') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= __('admin.listings') ?></h3>
                        <p class="mt-2 text-sm text-gray-600">Approve and manage property listings</p>
                    </div>
                    <svg class="h-8 w-8 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Manage Users -->
            <?php if (Auth::isSuperAdmin()): ?>
            <a href="<?= url('admin/users') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= __('admin.users') ?></h3>
                        <p class="mt-2 text-sm text-gray-600">Manage user accounts and roles</p>
                    </div>
                    <svg class="h-8 w-8 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
            <?php endif; ?>

            <!-- View Reports -->
            <a href="<?= url('admin/reports') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= __('admin.reports') ?></h3>
                        <p class="mt-2 text-sm text-gray-600">Review content reports</p>
                        <?php if ($pendingReports > 0): ?>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 mt-2">
                            <?= $pendingReports ?> pending
                        </span>
                        <?php endif; ?>
                    </div>
                    <svg class="h-8 w-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>

            <!-- Activity Logs -->
            <a href="<?= url('admin/logs') ?>" class="bg-white rounded-lg shadow p-6 hover:shadow-lg transition">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900"><?= __('admin.logs') ?></h3>
                        <p class="mt-2 text-sm text-gray-600">View admin activity logs</p>
                    </div>
                    <svg class="h-8 w-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </div>
            </a>
        </div>

        <!-- Pending Listings Table -->
        <?php if ($pendingListings > 0): ?>
        <div class="mt-8 bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Pending Approval</h3>
            </div>
            <div class="p-6">
                <p class="text-gray-600">
                    You have <?= $pendingListings ?> listing(s) waiting for approval.
                    <a href="<?= url('admin/listings?status=pending') ?>" class="text-blue-600 hover:text-blue-800 font-medium">
                        Review now →
                    </a>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
