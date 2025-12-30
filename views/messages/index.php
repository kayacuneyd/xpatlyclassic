<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900"><?= __('messages.title') ?? 'Messages' ?></h1>
            <p class="text-sm text-gray-600">
                <?= __('messages.inbox_description') ?? 'Messages received from interested parties' ?></p>
        </div>

        <!-- Messages List -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <?php if (empty($messages)): ?>
                <div class="text-center py-12">
                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                    <h3 class="text-lg font-medium text-gray-700 mb-2"><?= __('user.no_messages') ?? 'No messages yet' ?>
                    </h3>
                    <p class="text-gray-500">Messages from interested parties will appear here.</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-200">
                    <?php foreach ($messages as $message): ?>
                        <li class="p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <!-- Sender & Time -->
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="font-medium text-gray-900">
                                            <?= htmlspecialchars($message['sender_name'] ?? 'Anonymous') ?>
                                        </span>
                                        <span class="text-gray-400">•</span>
                                        <span class="text-sm text-gray-500">
                                            <?= htmlspecialchars($message['sender_email']) ?>
                                        </span>
                                    </div>

                                    <!-- Listing Reference -->
                                    <?php if (!empty($message['listing_title'])): ?>
                                        <div class="text-sm text-secondary-600 mb-2">
                                            Re: <a href="<?= url('listings/' . $message['listing_id']) ?>" class="hover:underline">
                                                <?= htmlspecialchars($message['listing_title']) ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>

                                    <!-- Message Content -->
                                    <p class="text-gray-700 text-sm">
                                        <?= nl2br(htmlspecialchars($message['message'])) ?>
                                    </p>

                                    <!-- Timestamp -->
                                    <p class="text-xs text-gray-400 mt-2">
                                        <?= date('M d, Y H:i', strtotime($message['created_at'])) ?>
                                    </p>
                                </div>

                                <!-- Reply Action -->
                                <a href="mailto:<?= htmlspecialchars($message['sender_email']) ?>"
                                    class="ml-4 text-secondary-600 hover:text-secondary-800" title="Reply">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                </a>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-6">
            <a href="<?= url('dashboard') ?>" class="text-gray-600 hover:text-gray-900">
                &larr; <?= __('common.dashboard') ?? 'Back to Dashboard' ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>