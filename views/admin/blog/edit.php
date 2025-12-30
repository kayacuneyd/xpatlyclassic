<?php require __DIR__ . '/../../layouts/header.php'; ?>

<!-- SunEditor WYSIWYG Editor -->
<link href="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/css/suneditor.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/dist/suneditor.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/suneditor@latest/src/lang/en.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const textareas = document.querySelectorAll('textarea[name^="content_"]');
        textareas.forEach(textarea => {
            const editor = SUNEDITOR.create(textarea, {
                buttonList: [
                    ['undo', 'redo'],
                    ['font', 'fontSize', 'formatBlock'],
                    ['paragraphStyle', 'blockquote'],
                    ['bold', 'underline', 'italic', 'strike', 'subscript', 'superscript'],
                    ['fontColor', 'hiliteColor', 'textStyle'],
                    ['removeFormat'],
                    ['outdent', 'indent'],
                    ['align', 'horizontalRule', 'list', 'lineHeight'],
                    ['table', 'link', 'image', 'video', 'audio'],
                    ['fullScreen', 'showBlocks', 'codeView'],
                    ['preview', 'print']
                ],
                height: '400px',
                lang: SUNEDITOR_LANG['en'],
                placeholder: 'Start writing your post here...'
            });

            // Sync content to textarea on change/submit
            editor.onChange = function (contents, core) {
                textarea.value = contents;
            };
        });
    });
</script>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="bg-white rounded-lg shadow">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-2xl font-bold"><?= $post ? 'Edit Post' : 'Create New Post' ?></h2>
            </div>

            <form action="<?= $post ? url('admin/blog/' . $post['id']) : url('admin/blog') ?>" method="POST"
                enctype="multipart/form-data" class="p-6">
                <?= csrf_field() ?>

                <!-- Tabs for Languages -->
                <div x-data="{ activeTab: 'en' }" class="mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="flex space-x-4" aria-label="Language tabs">
                            <button type="button" @click="activeTab = 'en'"
                                :class="activeTab === 'en' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-3 py-2 font-medium text-sm border-b-2 transition-colors">
                                <span class="flag-icon flag-icon-gb mr-1"></span> English *
                            </button>
                            <button type="button" @click="activeTab = 'et'"
                                :class="activeTab === 'et' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-3 py-2 font-medium text-sm border-b-2 transition-colors">
                                <span class="flag-icon flag-icon-ee mr-1"></span> Eesti
                            </button>
                            <button type="button" @click="activeTab = 'ru'"
                                :class="activeTab === 'ru' ? 'border-primary-500 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                                class="px-3 py-2 font-medium text-sm border-b-2 transition-colors">
                                <span class="flag-icon flag-icon-ru mr-1"></span> Русский
                            </button>
                        </nav>
                    </div>

                    <!-- English Tab -->
                    <div x-show="activeTab === 'en'" class="pt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Title (English) *</label>
                            <input type="text" name="title_en" required
                                value="<?= htmlspecialchars($post['title_en'] ?? '') ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Content (English) *</label>
                            <textarea name="content_en" rows="12" required
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($post['content_en'] ?? '') ?></textarea>
                            <p class="text-xs text-gray-500 mt-1">You can use HTML for formatting</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Title (SEO)</label>
                                <input type="text" name="meta_title_en"
                                    value="<?= htmlspecialchars($post['meta_title_en'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta Description
                                    (SEO)</label>
                                <input type="text" name="meta_description_en"
                                    value="<?= htmlspecialchars($post['meta_description_en'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- Estonian Tab -->
                    <div x-show="activeTab === 'et'" class="pt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Pealkiri (Eesti)</label>
                            <input type="text" name="title_et" value="<?= htmlspecialchars($post['title_et'] ?? '') ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Sisu (Eesti)</label>
                            <textarea name="content_et" rows="12"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($post['content_et'] ?? '') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta pealkiri (SEO)</label>
                                <input type="text" name="meta_title_et"
                                    value="<?= htmlspecialchars($post['meta_title_et'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Meta kirjeldus (SEO)</label>
                                <input type="text" name="meta_description_et"
                                    value="<?= htmlspecialchars($post['meta_description_et'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>

                    <!-- Russian Tab -->
                    <div x-show="activeTab === 'ru'" class="pt-4 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Заголовок (Русский)</label>
                            <input type="text" name="title_ru" value="<?= htmlspecialchars($post['title_ru'] ?? '') ?>"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Содержание (Русский)</label>
                            <textarea name="content_ru" rows="12"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500"><?= htmlspecialchars($post['content_ru'] ?? '') ?></textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Мета заголовок (SEO)</label>
                                <input type="text" name="meta_title_ru"
                                    value="<?= htmlspecialchars($post['meta_title_ru'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Мета описание (SEO)</label>
                                <input type="text" name="meta_description_ru"
                                    value="<?= htmlspecialchars($post['meta_description_ru'] ?? '') ?>"
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Featured Image & Status -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 border-t pt-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Featured Image</label>
                        <?php if (!empty($post['featured_image'])): ?>
                            <div class="mb-2">
                                <img src="<?= $post['featured_image'] ?>" alt="Current image"
                                    class="w-32 h-20 object-cover rounded">
                            </div>
                        <?php endif; ?>
                        <input type="file" name="featured_image" accept="image/*"
                            class="w-full px-4 py-2 border rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary-500">
                            <option value="draft" <?= ($post['status'] ?? 'draft') === 'draft' ? 'selected' : '' ?>>Draft
                            </option>
                            <option value="published" <?= ($post['status'] ?? '') === 'published' ? 'selected' : '' ?>>
                                Published</option>
                        </select>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3 pt-6 border-t mt-6">
                    <a href="<?= url('admin/blog') ?>"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</a>
                    <button type="submit" class="btn btn-primary">
                        <?= $post ? 'Update Post' : 'Create Post' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../layouts/footer.php'; ?>