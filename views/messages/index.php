<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6"><?= __('messages.title') ?? 'Messages' ?></h1>

        <!-- Tab Navigation -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <!-- BLUE TAB: Incoming Inquiries -->
                    <button onclick="switchTab('received')"
                            id="tab-received"
                            class="tab-button border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/>
                            </svg>
                            <?= __('messages.received_inquiries') ?? 'Gelen Talepler' ?>
                            <?php if ($unreadReceived > 0): ?>
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <?= $unreadReceived ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </button>

                    <!-- ORANGE TAB: Sent Inquiries -->
                    <button onclick="switchTab('sent')"
                            id="tab-sent"
                            class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            <?= __('messages.sent_inquiries') ?? 'Gönderilen Talepler' ?>
                            <?php if ($unreadSent > 0): ?>
                                <span class="bg-orange-100 text-orange-800 text-xs font-semibold px-2 py-0.5 rounded-full">
                                    <?= $unreadSent ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </button>
                </nav>
            </div>
        </div>

        <!-- BLUE CONTENT: Incoming Inquiries -->
        <div id="content-received" class="tab-content">
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                <p class="text-blue-800 text-sm">
                    <strong><?= __('messages.received_inquiries') ?? 'Gelen Talepler' ?>:</strong>
                    <?= __('messages.received_description') ?? 'Satışa/kiralığa çıkardığınız evler hakkında size gelen mesajlar' ?>
                </p>
            </div>

            <?php if (empty($receivedMessages)): ?>
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-600"><?= __('messages.no_received') ?? 'Henüz gelen mesajınız yok' ?></p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($receivedMessages as $msg): ?>
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                                            <span class="text-blue-700 font-semibold text-sm">
                                                <?= strtoupper(substr($msg['sender_name'] ?? 'A', 0, 1)) ?>
                                            </span>
                                        </div>
                                        <p class="font-semibold text-gray-900">
                                            <?= htmlspecialchars($msg['sender_name'] ?? 'Anonim') ?>
                                        </p>
                                        <span class="text-gray-400">•</span>
                                        <a href="mailto:<?= htmlspecialchars($msg['sender_email']) ?>"
                                           class="text-blue-600 hover:underline text-sm">
                                            <?= htmlspecialchars($msg['sender_email']) ?>
                                        </a>
                                    </div>

                                    <p class="text-sm text-gray-600 mb-3">
                                        <?= __('messages.about_your_listing') ?? 'İlanınız hakkında' ?>:
                                        <a href="<?= url('listings/' . $msg['listing_id']) ?>"
                                           class="text-blue-600 hover:underline font-medium">
                                            <?= htmlspecialchars($msg['listing_title']) ?>
                                        </a>
                                    </p>

                                    <p class="text-gray-800 whitespace-pre-line bg-gray-50 p-3 rounded">
                                        <?= htmlspecialchars($msg['message']) ?>
                                    </p>

                                    <p class="text-xs text-gray-500 mt-3">
                                        <?= date('M d, Y H:i', strtotime($msg['created_at'])) ?>
                                    </p>
                                </div>

                                <a href="mailto:<?= htmlspecialchars($msg['sender_email']) ?>"
                                   class="ml-4 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium transition-colors flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                    <?= __('common.reply') ?? 'Cevapla' ?>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- ORANGE CONTENT: Sent Inquiries -->
        <div id="content-sent" class="tab-content hidden">
            <div class="bg-orange-50 border-l-4 border-orange-500 p-4 mb-6">
                <p class="text-orange-800 text-sm">
                    <strong><?= __('messages.sent_inquiries') ?? 'Gönderilen Talepler' ?>:</strong>
                    <?= __('messages.sent_description') ?? 'Kiralamak/satın almak için başkalarının evlerine gönderdiğiniz mesajlar' ?>
                </p>
            </div>

            <?php if (empty($sentMessages)): ?>
                <div class="bg-white rounded-lg shadow p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <p class="text-gray-600"><?= __('messages.no_sent') ?? 'Henüz gönderdiğiniz mesaj yok' ?></p>
                </div>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($sentMessages as $msg): ?>
                        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-orange-500 hover:shadow-lg transition-shadow">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-orange-700 font-semibold text-sm">
                                            <?= strtoupper(substr($msg['owner_name'] ?? 'O', 0, 1)) ?>
                                        </span>
                                    </div>
                                    <p class="font-semibold text-gray-900">
                                        <?= __('messages.sent_to') ?? 'Gönderildi' ?>: <?= htmlspecialchars($msg['owner_name']) ?>
                                    </p>
                                    <span class="text-gray-400">•</span>
                                    <a href="mailto:<?= htmlspecialchars($msg['owner_email']) ?>"
                                       class="text-orange-600 hover:underline text-sm">
                                        <?= htmlspecialchars($msg['owner_email']) ?>
                                    </a>
                                </div>

                                <p class="text-sm text-gray-600 mb-3">
                                    <?= __('messages.about_listing') ?? 'İlan hakkında' ?>:
                                    <a href="<?= url('listings/' . $msg['listing_id']) ?>"
                                       class="text-orange-600 hover:underline font-medium">
                                        <?= htmlspecialchars($msg['listing_title']) ?> - €<?= number_format($msg['listing_price']) ?>
                                    </a>
                                </p>

                                <div class="bg-orange-50 border border-orange-200 p-3 rounded">
                                    <p class="text-xs text-orange-700 font-semibold mb-1"><?= __('messages.your_message') ?? 'Mesajınız' ?>:</p>
                                    <p class="text-gray-800 whitespace-pre-line">
                                        <?= htmlspecialchars($msg['message']) ?>
                                    </p>
                                </div>

                                <p class="text-xs text-gray-500 mt-3">
                                    <?= __('messages.sent_on') ?? 'Gönderildi' ?>: <?= date('M d, Y H:i', strtotime($msg['created_at'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Back to Dashboard -->
        <div class="mt-6">
            <a href="<?= url('dashboard') ?>" class="text-gray-600 hover:text-gray-900 inline-flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                <?= __('common.dashboard') ?? 'Back to Dashboard' ?>
            </a>
        </div>
    </div>
</div>

<script>
function switchTab(tabName) {
    // Update tab buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('border-blue-500', 'border-orange-500', 'text-blue-600', 'text-orange-600');
        btn.classList.add('border-transparent', 'text-gray-500');
    });

    // Update tab content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });

    // Show selected tab
    if (tabName === 'received') {
        document.getElementById('tab-received').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-received').classList.add('border-blue-500', 'text-blue-600');
        document.getElementById('content-received').classList.remove('hidden');
    } else {
        document.getElementById('tab-sent').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById('tab-sent').classList.add('border-orange-500', 'text-orange-600');
        document.getElementById('content-sent').classList.remove('hidden');
    }
}
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
