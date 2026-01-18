<?php require __DIR__ . '/../layouts/header.php'; ?>

<!-- Schema.org Article Markup -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Article",
    "headline": "<?= htmlspecialchars($post['title']) ?>",
    "datePublished": "<?= $post['published_at'] ?>",
    "dateModified": "<?= $post['updated_at'] ?>",
    "author": {
        "@type": "Person",
        "name": "<?= htmlspecialchars($post['author_name'] ?? 'Xpatly Team') ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "Xpatly",
        "logo": {
            "@type": "ImageObject",
            "url": "<?= url('assets/images/logo.png') ?>"
        }
    },
    <?php if (!empty($post['featured_image'])): ?>
        "image": "<?= $post['featured_image'] ?>",
    <?php endif; ?>
    "description": "<?= htmlspecialchars($post['meta_description'] ?? substr(strip_tags($post['content']), 0, 160)) ?>"
}
</script>

<div class="bg-white">
    <!-- Hero Header (matching contact page style) -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 text-white py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <time class="text-primary-200 text-sm mb-4 block">
                <?= date('F d, Y', strtotime($post['published_at'])) ?>
            </time>
            <h1 class="text-4xl font-bold mb-6">
                <?= htmlspecialchars($post['title']) ?>
            </h1>
            <?php if (!empty($post['author_name'])): ?>
                <p class="text-xl text-primary-100">
                    <?= __('blog.by') ?? 'By' ?> <?= htmlspecialchars($post['author_name']) ?>
                </p>
            <?php endif; ?>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <!-- Featured Image -->
                    <?php if (!empty($post['featured_image'])): ?>
                        <img src="<?= $post['featured_image'] ?>" alt="<?= htmlspecialchars($post['title']) ?>"
                            class="w-full h-64 md:h-96 object-cover" loading="lazy" width="1280" height="720">
                    <?php endif; ?>

                    <!-- Article Content -->
                    <div class="p-8 md:p-12">
                        <div class="prose prose-lg max-w-none">
                            <?= $post['content'] ?>
                        </div>
                    </div>
                </div>

                <!-- Comments Section -->
                <div class="mt-12" id="comments">
                    <div class="bg-white rounded-xl shadow-md p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">
                            <?= __('blog.comments') ?? 'Comments' ?> (<?= $commentCount ?>)
                        </h2>

                        <!-- Comment Form -->
                        <?php if (\Core\Auth::check()): ?>
                            <form action="<?= url('blog/' . $post['slug'] . '/comment') ?>" method="POST" class="mb-8">
                                <div class="mb-4">
                                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                        <?= __('blog.add_comment') ?? 'Add a Comment' ?>
                                    </label>
                                    <textarea id="content" name="content" rows="4" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                                        placeholder="<?= __('blog.comment_placeholder') ?? 'Share your thoughts...' ?>"></textarea>
                                    <p class="text-xs text-gray-500 mt-2">
                                        <?= __('blog.comment_note') ?? 'Your comment will be reviewed before being published.' ?>
                                    </p>
                                </div>
                                <button type="submit"
                                    class="btn btn-primary">
                                    <?= __('blog.submit_comment') ?? 'Submit Comment' ?>
                                </button>
                            </form>
                        <?php else: ?>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-6 mb-8 text-center">
                                <p class="text-gray-600 mb-4">
                                    <?= __('blog.login_to_comment') ?? 'Please login to leave a comment' ?>
                                </p>
                                <a href="<?= url('login') ?>" class="btn btn-primary">
                                    <?= __('auth.login') ?? 'Sign In' ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <!-- Comments List -->
                        <?php if (!empty($comments)): ?>
                            <div class="space-y-6">
                                <?php foreach ($comments as $comment): ?>
                                    <div class="border-b border-gray-200 pb-6 last:border-0 last:pb-0">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0">
                                                <div class="w-12 h-12 bg-primary-100 rounded-full flex items-center justify-center">
                                                    <span class="text-primary-700 font-semibold text-lg">
                                                        <?= strtoupper(substr($comment['user_name'] ?? 'U', 0, 1)) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-2">
                                                    <h4 class="font-semibold text-gray-900">
                                                        <?= htmlspecialchars($comment['user_name'] ?? 'User') ?>
                                                    </h4>
                                                    <time class="text-sm text-gray-500">
                                                        <?= date('M d, Y', strtotime($comment['created_at'])) ?>
                                                    </time>
                                                </div>
                                                <p class="text-gray-700 leading-relaxed">
                                                    <?= nl2br(htmlspecialchars($comment['content'])) ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-gray-500 text-center py-8">
                                <?= __('blog.no_comments') ?? 'No comments yet. Be the first to comment!' ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Back to Blog -->
                <div class="mt-8">
                    <a href="<?= url('blog') ?>"
                        class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7">
                            </path>
                        </svg>
                        <?= __('blog.back_to_blog') ?? 'Back to Blog' ?>
                    </a>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-md p-6 sticky top-24">
                    <h3 class="font-heading text-lg font-semibold text-gray-900 mb-4">
                        <?= __('blog.recent_posts') ?? 'Recent Posts' ?>
                    </h3>

                    <?php if (!empty($recentPosts)): ?>
                        <ul class="space-y-4">
                            <?php foreach ($recentPosts as $recent): ?>
                                <?php if ($recent['id'] !== $post['id']): ?>
                                    <li>
                                        <a href="<?= url('blog/' . $recent['slug']) ?>"
                                            class="block hover:bg-gray-50 p-2 rounded-lg transition-colors -mx-2">
                                            <h4 class="font-medium text-gray-900 text-sm line-clamp-2">
                                                <?= htmlspecialchars($recent['title']) ?>
                                            </h4>
                                            <time class="text-xs text-gray-500 mt-1 block">
                                                <?= date('M d, Y', strtotime($recent['published_at'])) ?>
                                            </time>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p class="text-gray-500 text-sm"><?= __('blog.no_recent') ?? 'No other posts yet' ?></p>
                    <?php endif; ?>

                    <hr class="my-6 border-gray-200">

                    <!-- Share Buttons -->
                    <h3 class="font-heading text-lg font-semibold text-gray-900 mb-4">
                        <?= __('blog.share') ?? 'Share' ?>
                    </h3>
                    <div class="flex gap-3">
                        <a href="https://twitter.com/intent/tweet?url=<?= urlencode(url('blog/' . $post['slug'])) ?>&text=<?= urlencode($post['title']) ?>"
                            target="_blank" rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-primary-100 rounded-full text-gray-600 hover:text-primary-600 transition-colors"
                            aria-label="Share on Twitter">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84">
                                </path>
                            </svg>
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(url('blog/' . $post['slug'])) ?>"
                            target="_blank" rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-primary-100 rounded-full text-gray-600 hover:text-primary-600 transition-colors"
                            aria-label="Share on Facebook">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z">
                                </path>
                            </svg>
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= urlencode(url('blog/' . $post['slug'])) ?>&title=<?= urlencode($post['title']) ?>"
                            target="_blank" rel="noopener"
                            class="w-10 h-10 flex items-center justify-center bg-gray-100 hover:bg-primary-100 rounded-full text-gray-600 hover:text-primary-600 transition-colors"
                            aria-label="Share on LinkedIn">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z">
                                </path>
                            </svg>
                        </a>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
