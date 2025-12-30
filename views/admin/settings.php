<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold">Site Settings</h2>
                <p class="text-sm text-gray-600 mt-1">Manage logo, icon, SEO metadata, and site information</p>
            </div>

            <form action="<?= url('admin/settings') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <div class="p-6 space-y-6">
                    <!-- Site Branding -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold mb-4">Branding</h3>

                        <!-- Site Name -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Name</label>
                            <input type="text" name="site_name"
                                value="<?= htmlspecialchars(settings('site_name', 'Xpatly')) ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600">
                        </div>

                        <!-- Site Tagline -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Tagline</label>
                            <input type="text" name="site_tagline"
                                value="<?= htmlspecialchars(settings('site_tagline', '')) ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600">
                        </div>

                        <!-- Logo Upload -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Site Logo</label>
                            <?php if (settings('site_logo')): ?>
                                <div class="mb-2">
                                    <img src="<?= settings('site_logo') ?>" alt="Current logo" class="h-16">
                                    <p class="text-xs text-gray-500 mt-1">Current logo</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="site_logo" accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Upload a new logo (PNG, JPG, SVG recommended)</p>
                        </div>

                        <!-- Icon/Favicon Upload -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Favicon</label>
                            <?php if (settings('site_icon')): ?>
                                <div class="mb-2">
                                    <img src="<?= settings('site_icon') ?>" alt="Current icon" class="h-8">
                                    <p class="text-xs text-gray-500 mt-1">Current favicon</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="site_icon" accept="image/*"
                                class="w-full px-4 py-2 border rounded-lg">
                            <p class="text-xs text-gray-500 mt-1">Upload favicon (16x16 or 32x32 PNG/ICO)</p>
                        </div>
                    </div>

                    <!-- SEO Settings -->
                    <div class="border-b pb-6">
                        <h3 class="text-lg font-semibold mb-4">SEO & Met a Tags</h3>

                        <!-- Meta Description -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                            <textarea name="meta_description" rows="3"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"><?= htmlspecialchars(settings('meta_description', '')) ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Max 160 characters recommended</p>
                        </div>

                        <!-- Meta Keywords -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Meta Keywords</label>
                            <textarea name="meta_keywords" rows="2"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"><?= htmlspecialchars(settings('meta_keywords', '')) ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">Comma-separated keywords</p>
                        </div>
                    </div>

                    <!-- Storage Settings (Super Admin Only) -->
                    <?php if (Core\Auth::isSuperAdmin()): ?>
                        <div class="border-b pb-6">
                            <h3 class="text-lg font-semibold mb-4">
                                <svg class="w-5 h-5 inline mr-1 text-secondary-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z">
                                    </path>
                                </svg>
                                Cloudflare R2 Storage
                            </h3>

                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Note:</strong> If R2 credentials are configured, listing images will be uploaded
                                    to Cloudflare R2.
                                    Otherwise, images are stored on the local server.
                                </p>
                                <?php if (\Core\StorageManager::isR2Available()): ?>
                                    <p class="text-sm text-green-700 mt-2 font-medium">✓ Currently using: Cloudflare R2</p>
                                <?php else: ?>
                                    <p class="text-sm text-gray-600 mt-2">Currently using: Local Storage</p>
                                <?php endif; ?>
                            </div>

                            <!-- R2 Access Key ID -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">R2 Access Key ID</label>
                                <input type="text" name="r2_access_key_id"
                                    value="<?= htmlspecialchars(settings('r2_access_key_id', '')) ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"
                                    placeholder="Enter your Cloudflare R2 Access Key ID">
                            </div>

                            <!-- R2 Secret Access Key -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">R2 Secret Access Key</label>
                                <input type="password" name="r2_secret_access_key"
                                    value="<?= htmlspecialchars(settings('r2_secret_access_key', '')) ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"
                                    placeholder="Enter your Cloudflare R2 Secret Access Key">
                            </div>

                            <!-- R2 Bucket Name -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">R2 Bucket Name</label>
                                <input type="text" name="r2_bucket_name"
                                    value="<?= htmlspecialchars(settings('r2_bucket_name', '')) ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"
                                    placeholder="e.g., xpatly-images">
                            </div>

                            <!-- R2 Endpoint -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">R2 Endpoint URL</label>
                                <input type="text" name="r2_endpoint"
                                    value="<?= htmlspecialchars(settings('r2_endpoint', '')) ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"
                                    placeholder="https://xxx.r2.cloudflarestorage.com">
                                <p class="text-xs text-gray-500 mt-1">Found in Cloudflare Dashboard → R2 → Bucket → Settings
                                </p>
                            </div>

                            <!-- R2 Public URL -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">R2 Public URL</label>
                                <input type="text" name="r2_public_url"
                                    value="<?= htmlspecialchars(settings('r2_public_url', '')) ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600"
                                    placeholder="https://images.yourdomain.com">
                                <p class="text-xs text-gray-500 mt-1">The public URL for accessing your R2 bucket (custom
                                    domain or r2.dev URL)</p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Contact Information -->
                    <div>
                        <h3 class="text-lg font-semibold mb-4">Contact Information</h3>

                        <!-- Contact Email -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                            <input type="email" name="contact_email"
                                value="<?= htmlspecialchars(settings('contact_email', '')) ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600">
                        </div>

                        <!-- Contact Phone -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                            <input type="text" name="contact_phone"
                                value="<?= htmlspecialchars(settings('contact_phone', '')) ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-600">
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <a href="<?= url('admin') ?>"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>