<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?= __('messages.title') ?? 'Messages' ?></h1>

        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <!-- BLUE TAB: Received -->
                    <a href="<?= url('messages?tab=received') ?>"
                       class="<?= $tab === 'received' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <?= __('messages.received_inquiries') ?? 'Received' ?>
                            <?php if ($unreadReceived > 0): ?>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <?= $unreadReceived ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>

                    <!-- ORANGE TAB: Sent -->
                    <a href="<?= url('messages?tab=sent') ?>"
                       class="<?= $tab === 'sent' ? 'border-orange-500 text-orange-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <?= __('messages.sent_inquiries') ?? 'Sent' ?>
                            <?php if ($unreadSent > 0): ?>
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <?= $unreadSent ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </a>
                </nav>
            </div>
        </div>

        <!-- Info Banner -->
        <div class="<?= $tab === 'received' ? 'bg-blue-50 border-blue-500' : 'bg-primary-50 border-primary-600' ?> border-l-4 p-4 mb-6">
            <p class="<?= $tab === 'received' ? 'text-blue-800' : 'text-primary-800' ?> text-sm">
                <strong><?= $tab === 'received' ? __('messages.received_inquiries') : __('messages.sent_inquiries') ?>:</strong>
                <?= $tab === 'received' ? __('messages.received_description') ?? 'Messages about your listings' : __('messages.sent_description') ?? 'Messages you sent to other listings' ?>
            </p>
        </div>

        <!-- Conversations List -->
        <?php if (empty($conversations)): ?>
            <div class="bg-white rounded-lg shadow p-12 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                </svg>
                <p class="text-gray-600"><?= __('messages.no_conversations') ?? 'No conversations yet' ?></p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($conversations as $conv): ?>
                    <a href="<?= url('messages/conversation/' . $conv['id']) ?>"
                       class="block bg-white rounded-lg shadow hover:shadow-lg transition-shadow <?= $conv['unread'] > 0 ? 'border-l-4 ' . ($tab === 'received' ? 'border-blue-500' : 'border-orange-500') : '' ?>">
                        <div class="p-4 flex gap-4">
                            <!-- User Avatar -->
                            <div class="flex-shrink-0">
                                <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-full flex items-center justify-center text-white font-bold text-xl">
                                    <?= strtoupper(substr(htmlspecialchars($conv['other_person_name']), 0, 1)) ?>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <!-- Listing Title -->
                                <h3 class="font-semibold text-gray-900 mb-1 truncate">
                                    <?= htmlspecialchars($conv['listing_title']) ?>
                                </h3>

                                <!-- Other Person -->
                                <p class="text-sm text-gray-600 mb-2">
                                    <?= $tab === 'received' ? __('messages.conversation_with') ?? 'With' : __('messages.listing_owner') ?? 'Owner' ?>:
                                    <span class="font-medium"><?= htmlspecialchars($conv['other_person_name']) ?></span>
                                </p>

                                <!-- Last Message Preview -->
                                <p class="text-sm text-gray-500 line-clamp-2">
                                    <?= htmlspecialchars(substr($conv['last_message_text'] ?? '', 0, 100)) ?>
                                    <?= strlen($conv['last_message_text'] ?? '') > 100 ? '...' : '' ?>
                                </p>
                            </div>

                            <!-- Right Side: Time & Badge -->
                            <div class="flex-shrink-0 text-right flex flex-col justify-between items-end">
                                <!-- Time -->
                                <p class="text-xs text-gray-500">
                                    <?php
                                    $time = strtotime($conv['last_message_at']);
                                    $diff = time() - $time;
                                    if ($diff < 3600) {
                                        echo floor($diff / 60) . ' min ago';
                                    } elseif ($diff < 86400) {
                                        echo floor($diff / 3600) . 'h ago';
                                    } elseif ($diff < 604800) {
                                        echo floor($diff / 86400) . 'd ago';
                                    } else {
                                        echo date('M d', $time);
                                    }
                                    ?>
                                </p>

                                <!-- Unread Badge -->
                                <?php if ($conv['unread'] > 0): ?>
                                    <span class="<?= $tab === 'received' ? 'bg-blue-500' : 'bg-orange-500' ?> text-white text-xs font-semibold px-2 py-1 rounded-full">
                                        <?= $conv['unread'] ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Pagination -->
            <?php if ($totalPages > 1): ?>
                <div class="mt-6 flex justify-center">
                    <nav class="flex gap-2">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <a href="<?= url("messages?tab={$tab}&page={$i}") ?>"
                               class="<?= $i == $page ? 'bg-blue-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' ?> px-4 py-2 rounded-lg border text-sm font-medium transition-colors">
                                <?= $i ?>
                            </a>
                        <?php endfor; ?>
                    </nav>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Back to Dashboard -->
        <div class="mt-8">
            <a href="<?= url('dashboard') ?>" class="text-gray-600 hover:text-gray-900 inline-flex items-center text-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <?= __('common.dashboard') ?? 'Back to Dashboard' ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
