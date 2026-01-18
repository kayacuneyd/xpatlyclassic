<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Listings</h1>
        <a href="/listings/create" class="btn btn-primary inline-flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create New Listing
        </a>
    </div>

    <?php $statusFilter = $_GET['status'] ?? ''; ?>
    <!-- Filter Tabs -->
    <div class="mb-6 border-b border-gray-200">
        <nav class="-mb-px flex flex-wrap gap-6">
            <a href="?status=" class="border-b-2 <?= empty($statusFilter) ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                All (<?= $counts['all'] ?? count($listings) ?>)
            </a>
            <a href="?status=active" class="border-b-2 <?= $statusFilter === 'active' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                Active (<?= $counts['active'] ?? 0 ?>)
            </a>
            <a href="?status=pending" class="border-b-2 <?= $statusFilter === 'pending' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                Pending (<?= $counts['pending'] ?? 0 ?>)
            </a>
            <a href="?status=paused" class="border-b-2 <?= $statusFilter === 'paused' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                Paused (<?= $counts['paused'] ?? 0 ?>)
            </a>
            <a href="?status=archived" class="border-b-2 <?= $statusFilter === 'archived' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                Archived (<?= $counts['archived'] ?? 0 ?>)
            </a>
            <a href="?status=draft" class="border-b-2 <?= $statusFilter === 'draft' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                Drafts (<?= $counts['draft'] ?? 0 ?>)
            </a>
        </nav>
    </div>

    <!-- Listings Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Listing</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Views</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($listings)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                            <?php if (!empty($statusFilter)): ?>
                                You have no <?= strtoupper($statusFilter) ?> listings right now.
                            <?php else: ?>
                                You don't have any listings yet.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($listings as $listing): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <?php if (!empty($listing['primary_image'])): ?>
                                        <img src="/uploads/listings/<?= $listing['id'] ?>/<?= $listing['primary_image'] ?>"
                                             class="w-16 h-16 object-cover rounded mr-4"
                                             alt="<?= htmlspecialchars($listing['title']) ?>">
                                    <?php endif; ?>
                                    <div>
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($listing['title']) ?></div>
                                        <div class="text-sm text-gray-500">
                                            <?= htmlspecialchars($listing['settlement']) ?>, <?= htmlspecialchars($listing['region']) ?>
                                        </div>
                                        <?php if ($listing['expat_friendly']): ?>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 mt-1">
                                                Expat-Friendly
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'active' => 'bg-green-100 text-green-800',
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'paused' => 'bg-gray-100 text-gray-800',
                                    'archived' => 'bg-red-100 text-red-800',
                                    'draft' => 'bg-blue-100 text-blue-800'
                                ];
                                $color = $statusColors[$listing['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?= $color ?>">
                                    <?= ucfirst($listing['status']) ?>
                                </span>
                                <?php if (!$listing['is_available']): ?>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 ml-1">
                                        Not Available
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if (!empty($listing['price'])): ?>
                                    €<?= number_format($listing['price'], 0) ?>/mo
                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $listing['views'] ?? 0 ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($listing['created_at'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end gap-2">
                                    <a href="/listings/<?= $listing['id'] ?>" class="text-primary-600 hover:text-primary-900" title="View">
                                        👁️
                                    </a>
                                    <a href="/listings/<?= $listing['id'] ?>/edit" class="text-blue-600 hover:text-blue-900" title="Edit">
                                        ✏️
                                    </a>
                                    <form method="POST" action="/listings/<?= $listing['id'] ?>/toggle-availability" class="inline">
                                        <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">
                                        <button type="submit" class="text-yellow-600 hover:text-yellow-900" title="Toggle Availability">
                                            <?= $listing['is_available'] ? '⏸️' : '▶️' ?>
                                        </button>
                                    </form>
                                    <form method="POST" action="/listings/<?= $listing['id'] ?>/delete"
                                          onsubmit="return confirm('Are you sure you want to delete this listing?')"
                                          class="inline">
                                        <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900" title="Delete">
                                            🗑️
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
