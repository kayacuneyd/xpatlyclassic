<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-gray-900">
                <?= __('auth.verify_info_title') ?? 'Verify your email' ?>
            </h2>
            <p class="mt-2 text-gray-600">
                <?= __('auth.verify_info_subtitle') ?? 'We sent a verification link to your email address.' ?>
            </p>
        </div>

        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 text-sm text-yellow-800">
            <p class="font-medium">
                <?= __('auth.verify_info_tip_title') ?? 'Next steps:' ?>
            </p>
            <ul class="list-disc pl-5 mt-2 space-y-1">
                <li><?= __('auth.verify_info_tip_1') ?? 'Check your inbox and spam folder.' ?></li>
                <li><?= __('auth.verify_info_tip_2') ?? 'Click the verification link to activate your account.' ?></li>
                <li><?= __('auth.verify_info_tip_3') ?? 'After verification, you can log in.' ?></li>
            </ul>
        </div>

        <div class="mt-6">
            <a href="<?= url('login') ?>" class="w-full btn btn-primary text-center inline-block">
                <?= __('auth.go_to_login') ?? 'Go to login' ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
