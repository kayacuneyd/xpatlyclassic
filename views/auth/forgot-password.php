<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-md mx-auto bg-white shadow-xl rounded-2xl p-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2"><?= __('auth.forgot_password') ?? 'Forgot Password' ?></h1>
        <p class="text-sm text-gray-600 mb-6">
            <?= __('auth.reset_instructions') ?? 'Enter your email and we will send you a password reset link.' ?>
        </p>

        <form action="<?= url('forgot-password') ?>" method="POST" class="space-y-4">
            <?= csrf_field() ?>
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?= __('auth.email') ?? 'Email' ?></label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                       placeholder="you@example.com">
            </div>

            <button type="submit"
                    class="w-full py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-lg shadow focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition">
                <?= __('auth.send_reset_link') ?? 'Send reset link' ?>
            </button>
        </form>

        <div class="mt-6 text-center">
            <a href="<?= url('login') ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.back_to_login') ?? 'Back to login' ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
