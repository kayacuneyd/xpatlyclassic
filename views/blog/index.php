<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="bg-white">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl font-bold mb-6"><?= __('blog.title') ?? 'Blog' ?></h1>
            <p class="text-xl text-primary-100 max-w-3xl mx-auto leading-relaxed">
                <?= __('blog.subtitle') ?? 'Latest news, tips and stories about expat life in Estonia' ?>
            </p>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

        <?php if (empty($posts)): ?>
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-700 mb-2"><?= __('blog.no_posts') ?? 'No posts yet' ?></h3>
                <p class="text-gray-500"><?= __('blog.check_back') ?? 'Check back soon for new content!' ?></p>
            </div>
        <?php else: ?>
            <!-- Blog Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($posts as $post): ?>
                    <article class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-shadow">
                        <!-- Featured Image -->
                        <?php if (!empty($post['featured_image'])): ?>
                            <a href="<?= url('blog/' . $post['slug']) ?>">
                                <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>"
                                    class="w-full h-48 object-cover" loading="lazy" width="600" height="360">
                            </a>
                        <?php else: ?>
                            <div
                                class="w-full h-48 bg-gradient-to-br from-primary-500 to-secondary-600 flex items-center justify-center">
                                <svg class="w-12 h-12 text-white opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2">
                                    </path>
                                </svg>
                            </div>
                        <?php endif; ?>

                        <!-- Content -->
                        <div class="p-6">
                            <time class="text-sm text-gray-500">
                                <?= date('M d, Y', strtotime($post['published_at'])) ?>
                            </time>
                            <h2 class="font-heading text-xl font-semibold text-gray-900 mt-2 mb-3">
                                <a href="<?= url('blog/' . $post['slug']) ?>" class="hover:text-primary-600 transition-colors">
                                    <?= htmlspecialchars($post['title']) ?>
                                </a>
                            </h2>
                            <p class="text-gray-600 text-sm line-clamp-3">
                                <?= htmlspecialchars(substr(strip_tags($post['content']), 0, 150)) ?>...
                            </p>
                            <a href="<?= url('blog/' . $post['slug']) ?>"
                                class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm mt-4"
                                aria-label="<?= __('blog.read_more') ? __('blog.read_more') . ': ' . htmlspecialchars($post['title']) : 'Read more: ' . htmlspecialchars($post['title']) ?>">
                                <?= __('blog.read_more') ?? 'Read More' ?>
                                <span class="sr-only">: <?= htmlspecialchars($post['title']) ?></span>
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                                    </path>
                                </svg>
                            </a>
                        </div>
                    </article>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <nav class="flex justify-center mt-12 gap-2">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=<?= $currentPage - 1 ?>"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            <?= __('common.previous') ?? 'Previous' ?>
                        </a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?= $i ?>"
                            class="px-4 py-2 rounded-lg <?= $i === $currentPage ? 'bg-primary-500 text-white' : 'bg-white border border-gray-300 hover:bg-gray-50 text-gray-700' ?>">
                            <?= $i ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?= $currentPage + 1 ?>"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 text-gray-700">
                            <?= __('common.next') ?? 'Next' ?>
                        </a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
