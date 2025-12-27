<?php
use Core\Auth;

Auth::requireRole(['super_admin', 'moderator']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900"><?= __('admin.listings') ?></h1>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="<?= url('admin/listings') ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <input type="text"
                           name="q"
                           value="<?= e($query ?? '') ?>"
                           placeholder="Search listings..."
                           class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    <select name="status" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Statuses</option>
                        <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="active" <?= ($filters['status'] ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                        <option value="paused" <?= ($filters['status'] ?? '') === 'paused' ? 'selected' : '' ?>>Paused</option>
                        <option value="archived" <?= ($filters['status'] ?? '') === 'archived' ? 'selected' : '' ?>>Archived</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <!-- Listings Table -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($listings)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No listings found
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($listings as $listing): ?>
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900"><?= e($listing['title']) ?></div>
                                <div class="text-sm text-gray-500"><?= e($listing['address']) ?></div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <?= e($listing['owner_name'] ?? 'Unknown') ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                €<?= number_format($listing['price'], 0) ?>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                    <?= $listing['status'] === 'active' ? 'bg-green-100 text-green-800' : '' ?>
                                    <?= $listing['status'] === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' ?>
                                    <?= $listing['status'] === 'paused' ? 'bg-gray-100 text-gray-800' : '' ?>">
                                    <?= ucfirst($listing['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm space-x-2">
                                <a href="<?= url("listings/{$listing['id']}") ?>"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-900">View</a>
                                <a href="<?= url("admin/listings/{$listing['id']}/edit") ?>"
                                   class="text-green-600 hover:text-green-900">Edit</a>

                                <?php if ($listing['status'] === 'pending'): ?>
                                <form method="POST" action="<?= url("admin/listings/{$listing['id']}/status") ?>" class="inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="status" value="active">
                                    <button type="submit" class="text-green-600 hover:text-green-900">Approve</button>
                                </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
