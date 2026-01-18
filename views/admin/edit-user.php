<?php
use Core\Auth;

Auth::requireRole(['super_admin']);

require __DIR__ . '/../layouts/header.php';
?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="<?= url('admin/users') ?>"
                class="inline-flex items-center text-primary-600 hover:text-primary-800 mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to Users
            </a>
            <h1 class="text-3xl font-bold text-gray-900">Edit User</h1>
        </div>

        <!-- User Info Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <span class="text-primary-700 font-bold text-2xl">
                        <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                    </span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900"><?= htmlspecialchars($user['full_name']) ?></h2>
                    <p class="text-gray-600"><?= htmlspecialchars($user['email']) ?></p>
                    <p class="text-sm text-gray-500 mt-1">User ID: <?= $user['id'] ?> | Registered:
                        <?= date('M d, Y', strtotime($user['created_at'])) ?></p>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form method="POST" action="<?= url('admin/users/' . $user['id'] . '/edit') ?>" class="space-y-6">
                <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

                <!-- Full Name -->
                <div>
                    <label for="full_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Full Name *
                    </label>
                    <input type="text" id="full_name" name="full_name"
                        value="<?= htmlspecialchars($user['full_name']) ?>" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <!-- Email (Read-only) -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Email Address
                    </label>
                    <input type="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" disabled
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-500 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Email cannot be changed for security reasons</p>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                        Phone Number
                    </label>
                    <input type="tel" id="phone" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        placeholder="+372 5XXX XXXX">
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                        User Role *
                    </label>
                    <select id="role" name="role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User (Renter/Buyer)</option>
                        <option value="owner" <?= $user['role'] === 'owner' ? 'selected' : '' ?>>Owner (Landlord/Seller)
                        </option>
                        <option value="moderator" <?= $user['role'] === 'moderator' ? 'selected' : '' ?>>Moderator</option>
                        <option value="super_admin" <?= $user['role'] === 'super_admin' ? 'selected' : '' ?>>Super Admin
                        </option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">
                        <strong>User:</strong> Can browse and save listings |
                        <strong>Owner:</strong> Can create listings |
                        <strong>Moderator:</strong> Can manage listings |
                        <strong>Super Admin:</strong> Full access
                    </p>
                </div>

                <!-- Locale -->
                <div>
                    <label for="locale" class="block text-sm font-medium text-gray-700 mb-2">
                        Preferred Language
                    </label>
                    <select id="locale" name="locale"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        <option value="en" <?= $user['locale'] === 'en' ? 'selected' : '' ?>>English</option>
                        <option value="et" <?= $user['locale'] === 'et' ? 'selected' : '' ?>>Eesti</option>
                        <option value="ru" <?= $user['locale'] === 'ru' ? 'selected' : '' ?>>Русский</option>
                    </select>
                </div>

                <!-- Password Reset Section -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Set New Password</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Leave blank to keep current password. If you set a new password, the user will need to use it to
                        login.
                    </p>

                    <div class="space-y-4">
                        <div>
                            <label for="new_password" class="block text-sm font-medium text-gray-700 mb-2">
                                New Password (optional)
                            </label>
                            <input type="password" id="new_password" name="new_password" minlength="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Enter new password (min 8 characters)">
                            <p class="text-xs text-gray-500 mt-1">Minimum 8 characters</p>
                        </div>

                        <div>
                            <label for="confirm_password" class="block text-sm font-medium text-gray-700 mb-2">
                                Confirm New Password
                            </label>
                            <input type="password" id="confirm_password" name="confirm_password" minlength="8"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                placeholder="Confirm new password">
                        </div>
                    </div>

                    <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded flex items-start gap-2">
                        <svg class="w-5 h-5 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                        <div class="text-sm text-yellow-800">
                            <strong>Warning:</strong> Setting a new password will immediately update the user's login
                            credentials.
                            Make sure to communicate the new password to the user securely.
                        </div>
                    </div>
                </div>

                <!-- Verification Status -->
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Verification Status</h3>

                    <div class="space-y-4">
                        <!-- Email Verification -->
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="font-medium text-gray-900">Email Verification</p>
                                    <p class="text-sm text-gray-500"><?= htmlspecialchars($user['email']) ?></p>
                                </div>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="email_verified" class="sr-only peer"
                                    <?= $user['email_verified'] ? 'checked' : '' ?>>
                                <div
                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600">
                                </div>
                            </label>
                        </div>
                    </div>

                    <p class="text-sm text-gray-600 mt-4 p-3 bg-blue-50 border border-blue-200 rounded">
                        <strong>Note:</strong> Users must have email verified to create listings.
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <button type="button"
                        onclick="if(confirm('Are you sure you want to delete this user? This action cannot be undone and will delete all their listings.')) { document.getElementById('deleteForm').submit(); }"
                        class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium transition-colors">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete User
                    </button>

                    <div class="flex gap-3">
                        <a href="<?= url('admin/users') ?>"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                            Cancel
                        </a>
                        <button type="submit"
                            class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-medium transition-colors">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Save Changes
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- User Statistics (if owner) -->
        <?php if (in_array($user['role'], ['owner', 'super_admin'])): ?>
            <div class="bg-white rounded-lg shadow-md p-6 mt-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Statistics</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Total Listings</p>
                        <p class="text-2xl font-bold text-gray-900"><?= \Models\User::getOwnerListingsCount($user['id']) ?>
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Member Since</p>
                        <p class="text-lg font-semibold text-gray-900"><?= date('M Y', strtotime($user['created_at'])) ?>
                        </p>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <p class="text-sm text-gray-600">Last Updated</p>
                        <p class="text-lg font-semibold text-gray-900"><?= date('M d, Y', strtotime($user['updated_at'])) ?>
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Hidden Delete Form -->
<form id="deleteForm" method="POST" action="<?= url('admin/users/' . $user['id'] . '/delete') ?>"
    style="display: none;">
    <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">
</form>

<?php require __DIR__ . '/../layouts/footer.php'; ?>