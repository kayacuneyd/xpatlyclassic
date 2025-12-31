<?php
use Core\Auth;

Auth::requireRole(['super_admin', 'moderator']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-gray-900"><?= __('admin.reports') ?? 'Reports' ?></h1>

            <!-- Status Filter -->
            <form method="GET" class="flex gap-2">
                <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent" onchange="this.form.submit()">
                    <option value=""><?= __('admin.all_reports') ?? 'All Reports' ?></option>
                    <option value="pending" <?= ($filters['status'] ?? '') === 'pending' ? 'selected' : '' ?>><?= __('admin.pending') ?? 'Pending' ?></option>
                    <option value="reviewed" <?= ($filters['status'] ?? '') === 'reviewed' ? 'selected' : '' ?>><?= __('admin.reviewed') ?? 'Reviewed' ?></option>
                    <option value="dismissed" <?= ($filters['status'] ?? '') === 'dismissed' ? 'selected' : '' ?>><?= __('admin.dismissed') ?? 'Dismissed' ?></option>
                </select>
            </form>
        </div>

        <?php if (empty($reports)): ?>
            <div class="bg-white rounded-lg shadow p-6 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-600"><?= __('admin.no_reports_found') ?? 'No reports found.' ?></p>
            </div>
        <?php else: ?>
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.listing') ?? 'Listing' ?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.reason') ?? 'Reason' ?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.reporter') ?? 'Reporter' ?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.date') ?? 'Date' ?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.status') ?? 'Status' ?></th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"><?= __('admin.actions') ?? 'Actions' ?></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($reports as $report): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">#<?= $report['id'] ?></td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="<?= url('listings/' . $report['listing_id']) ?>"
                                       class="text-primary-600 hover:text-primary-800 hover:underline font-medium" target="_blank">
                                        <?= htmlspecialchars($report['listing_title'] ?? 'Listing #' . $report['listing_id']) ?>
                                    </a>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?= __('admin.owner') ?? 'Owner' ?>: <?= htmlspecialchars($report['owner_name'] ?? 'Unknown') ?>
                                    </p>
                                </td>
                                <td class="px-6 py-4 text-sm max-w-md">
                                    <p class="line-clamp-2 text-gray-700"><?= htmlspecialchars($report['reason']) ?></p>
                                    <?php if (!empty($report['admin_notes'])): ?>
                                        <p class="text-xs text-gray-500 mt-1 italic">
                                            <?= __('admin.notes') ?? 'Notes' ?>: <?= htmlspecialchars($report['admin_notes']) ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                    <?= $report['reporter_email'] ? htmlspecialchars($report['reporter_email']) : '<span class="text-gray-400">' . (__('admin.anonymous') ?? 'Anonymous') . '</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M d, Y', strtotime($report['created_at'])) ?>
                                    <p class="text-xs"><?= date('H:i', strtotime($report['created_at'])) ?></p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'reviewed' => 'bg-green-100 text-green-800',
                                        'dismissed' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $color = $statusColors[$report['status']] ?? 'bg-gray-100 text-gray-800';
                                    ?>
                                    <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $color ?>">
                                        <?= ucfirst($report['status']) ?>
                                    </span>
                                    <?php if ($report['status'] !== 'pending' && !empty($report['reviewed_by_name'])): ?>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <?= __('admin.by') ?? 'by' ?> <?= htmlspecialchars($report['reviewed_by_name']) ?>
                                        </p>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <?php if ($report['status'] === 'pending'): ?>
                                        <button onclick="openReviewModal(<?= $report['id'] ?>, '<?= htmlspecialchars($report['listing_title'] ?? '', ENT_QUOTES) ?>', '<?= htmlspecialchars($report['reason'] ?? '', ENT_QUOTES) ?>')"
                                                class="text-primary-600 hover:text-primary-900 font-medium">
                                            <?= __('admin.review') ?? 'Review' ?>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-gray-400">-</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Review Modal -->
<div id="reviewModal" class="hidden fixed inset-0 bg-black/50 z-50 p-4" style="display: none; align-items: center; justify-content: center;">
    <div class="bg-white rounded-lg max-w-2xl w-full p-6 max-h-[90vh] overflow-y-auto">
        <h2 class="text-2xl font-bold text-gray-900 mb-4"><?= __('admin.review_report') ?? 'Review Report' ?></h2>

        <!-- Report Details -->
        <div class="bg-gray-50 rounded-lg p-4 mb-6">
            <div class="mb-2">
                <span class="text-sm font-medium text-gray-500"><?= __('admin.listing') ?? 'Listing' ?>:</span>
                <span id="modalListing" class="text-sm text-gray-900 ml-2"></span>
            </div>
            <div>
                <span class="text-sm font-medium text-gray-500"><?= __('admin.report_reason') ?? 'Report Reason' ?>:</span>
                <p id="modalReason" class="text-sm text-gray-700 mt-1"></p>
            </div>
        </div>

        <form id="reviewForm" method="POST" action="">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.decision') ?? 'Decision' ?> *</label>
                <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    <option value="reviewed"><?= __('admin.mark_reviewed') ?? 'Mark as Reviewed' ?></option>
                    <option value="dismissed"><?= __('admin.dismiss_report') ?? 'Dismiss Report' ?></option>
                </select>
                <p class="text-xs text-gray-500 mt-1">
                    <?= __('admin.review_help') ?? 'Reviewed: Report was investigated. Dismissed: Report was invalid or spam.' ?>
                </p>
            </div>

            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2"><?= __('admin.admin_notes') ?? 'Admin Notes' ?> (<?= __('common.optional') ?? 'Optional' ?>)</label>
                <textarea name="notes" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                          placeholder="<?= __('admin.notes_placeholder') ?? 'Add internal notes about this report...' ?>"></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeReviewModal()"
                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    <?= __('common.cancel') ?? 'Cancel' ?>
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors">
                    <?= __('admin.submit_review') ?? 'Submit Review' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function openReviewModal(reportId, listingTitle, reason) {
    document.getElementById('reviewForm').action = `/admin/reports/${reportId}/review`;
    document.getElementById('modalListing').textContent = listingTitle;
    document.getElementById('modalReason').textContent = reason;
    const modal = document.getElementById('reviewModal');
    modal.classList.remove('hidden');
    modal.style.display = 'flex';
}

function closeReviewModal() {
    const modal = document.getElementById('reviewModal');
    modal.classList.add('hidden');
    modal.style.display = 'none';
    // Reset form
    document.getElementById('reviewForm').reset();
}

// Close modal on outside click
document.getElementById('reviewModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeReviewModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeReviewModal();
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
