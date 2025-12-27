<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">My Listings</h1>
        <a href="/listings/create" class="btn btn-primary">
            ➕ Create New Listing
        </a>
    </div>

    <?php if (empty($listings)): ?>
        <div class="bg-white rounded-lg shadow-lg p-12 text-center">
            <p class="text-gray-500 text-lg mb-4">You don't have any listings yet</p>
            <a href="/listings/create" class="btn btn-primary inline-block">
                Create Your First Listing
            </a>
        </div>
    <?php else: ?>
        <!-- Filter Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8">
                <a href="?status=" class="border-b-2 <?= empty($_GET['status']) ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                    All (<?= count($listings) ?>)
                </a>
                <a href="?status=active" class="border-b-2 <?= ($_GET['status'] ?? '') === 'active' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                    Active
                </a>
                <a href="?status=pending" class="border-b-2 <?= ($_GET['status'] ?? '') === 'pending' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                    Pending
                </a>
                <a href="?status=paused" class="border-b-2 <?= ($_GET['status'] ?? '') === 'paused' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700' ?> px-1 pb-4 text-sm font-medium">
                    Paused
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
                                                ✓ Expat-Friendly
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
                                    'archived' => 'bg-red-100 text-red-800'
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
                                €<?= number_format($listing['price'], 0) ?>/mo
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
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
