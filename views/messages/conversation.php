<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="<?= url('messages') ?>" class="text-gray-600 hover:text-gray-900 inline-flex items-center text-sm mb-4">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <?= __('messages.back_to_messages') ?? 'Back to Messages' ?>
            </a>

            <h1 class="text-2xl font-bold text-gray-900">
                <?= __('messages.conversation_title') ?? 'Conversation' ?>
            </h1>
        </div>

        <!-- Listing Card -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 flex gap-4 items-center">
            <img src="<?= $conversation['listing_image'] ? '/uploads/listings/' . $conversation['listing_id'] . '/' . htmlspecialchars($conversation['listing_image']) : '/images/placeholder.jpg' ?>"
                 alt="<?= htmlspecialchars($conversation['listing_title']) ?>"
                 class="w-24 h-24 object-cover rounded-lg">

            <div class="flex-1">
                <h2 class="text-lg font-semibold text-gray-900">
                    <?= htmlspecialchars($conversation['listing_title']) ?>
                </h2>
                <p class="text-sm text-gray-600">
                    €<?= number_format($conversation['listing_price']) ?>/month
                </p>
            </div>

            <a href="<?= url('listings/' . $conversation['listing_id']) ?>"
               class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium transition-colors">
                <?= __('messages.view_listing') ?? 'View Listing' ?>
            </a>
        </div>

        <!-- Conversation Participant Info -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-800 text-sm">
                <strong><?= __('messages.conversation_with') ?? 'Conversation with' ?>:</strong>
                <?php
                $otherPerson = ($userType === 'user1') ? $conversation['user2_name'] : $conversation['user1_name'];
                echo htmlspecialchars($otherPerson);
                ?>
            </p>
        </div>

        <!-- Messages Thread -->
        <div class="bg-white rounded-lg shadow-md mb-6">
            <div class="p-6 max-h-[600px] overflow-y-auto space-y-4" id="messages-container">
                <?php foreach ($messages as $msg): ?>
                    <?php
                    $isCurrentUser = $msg['sender_type'] === $userType;
                    $alignClass = $isCurrentUser ? 'items-end' : 'items-start';
                    $bgClass = $isCurrentUser ? 'bg-blue-100' : 'bg-gray-100';
                    $textAlign = $isCurrentUser ? 'text-right' : 'text-left';
                    ?>

                    <div class="flex flex-col <?= $alignClass ?>">
                        <!-- Sender Name & Time -->
                        <div class="flex items-center gap-2 mb-1 <?= $isCurrentUser ? 'flex-row-reverse' : '' ?>">
                            <p class="text-xs font-semibold text-gray-700">
                                <?= htmlspecialchars($msg['sender_name']) ?>
                            </p>
                            <span class="text-xs text-gray-500">
                                <?= date('M d, Y H:i', strtotime($msg['created_at'])) ?>
                            </span>
                        </div>

                        <!-- Message Bubble -->
                        <div class="max-w-md <?= $bgClass ?> rounded-lg p-3 <?= $textAlign ?>">
                            <p class="text-gray-800 whitespace-pre-line break-words">
                                <?= nl2br(htmlspecialchars($msg['message'])) ?>
                            </p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Reply Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <?= __('messages.send_reply') ?? 'Send Reply' ?>
            </h3>

            <form method="POST" action="<?= url('messages/conversation/' . $conversation['id'] . '/reply') ?>">
                <div class="mb-4">
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                        <?= __('messages.your_message') ?? 'Your Message' ?>
                    </label>
                    <textarea name="message"
                              id="message"
                              rows="4"
                              required
                              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                              placeholder="<?= __('messages.reply_placeholder') ?? 'Type your reply...' ?>"></textarea>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="<?= url('messages') ?>"
                       class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                        <?= __('common.cancel') ?? 'Cancel' ?>
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                        </svg>
                        <?= __('messages.send_reply') ?? 'Send Reply' ?>
                    </button>
                </div>
                <?= csrf_field() ?>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-scroll to bottom of messages on page load
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('messages-container');
    if (container) {
        container.scrollTop = container.scrollHeight;
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
