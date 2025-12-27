<?php
use Core\Auth;

Auth::requireRole(['super_admin']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?= __('admin.users') ?></h1>

        <!-- Search and Filters -->
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" action="<?= url('admin/users') ?>" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text"
                           name="q"
                           value="<?= e($query ?? '') ?>"
                           placeholder="Search by name, email, or phone..."
                           class="col-span-2 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">

                    <select name="role" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All Roles</option>
                        <option value="renter" <?= ($filters['role'] ?? '') === 'renter' ? 'selected' : '' ?>>Renter</option>
                        <option value="owner" <?= ($filters['role'] ?? '') === 'owner' ? 'selected' : '' ?>>Owner</option>
                        <option value="super_admin" <?= ($filters['role'] ?? '') === 'super_admin' ? 'selected' : '' ?>>Super Admin</option>
                        <option value="moderator" <?= ($filters['role'] ?? '') === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                    </select>

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                        Search
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <p class="text-lg font-medium">No users found</p>
                            <p class="text-sm mt-1">Try adjusting your search or filters</p>
                        </td>
                    </tr>
                    <?php else: ?>
                    <?php foreach ($users as $user): ?>
                    <tr>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900"><?= e($user['full_name']) ?></div>
                            <div class="text-sm text-gray-500"><?= e($user['email']) ?></div>
                        </td>
                        <td class="px-6 py-4 text-sm"><?= ucfirst($user['role']) ?></td>
                        <td class="px-6 py-4">
                            <?php if ($user['email_verified']): ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Verified
                            </span>
                            <?php else: ?>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Pending
                            </span>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 text-sm space-x-2">
                            <a href="<?= url("admin/users/{$user['id']}/edit") ?>" class="text-blue-600 hover:text-blue-900">Edit</a>
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
