<?php
use Core\Auth;

Auth::requireRole(['super_admin', 'moderator']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?= __('admin.reports') ?></h1>

        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600">Content reports management interface</p>
            <p class="mt-2 text-sm text-gray-500">Reports feature is available but no reports submitted yet.</p>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
