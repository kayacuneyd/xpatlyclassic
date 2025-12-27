<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900"><?= __('auth.register') ?></h2>
            <p class="mt-2 text-gray-600">Create your free account</p>
        </div>

        <form method="POST" action="/register" class="space-y-6">
            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

            <div>
                <label class="label"><?= __('auth.full_name') ?></label>
                <input type="text" name="full_name" required class="input" placeholder="John Doe">
            </div>

            <div>
                <label class="label"><?= __('auth.email') ?></label>
                <input type="email" name="email" required class="input" placeholder="your@email.com">
            </div>

            <div>
                <label class="label"><?= __('auth.phone') ?></label>
                <input type="tel" name="phone" required class="input" placeholder="+372 5xxx xxxx">
            </div>

            <div>
                <label class="label"><?= __('auth.password') ?></label>
                <input type="password" name="password" required class="input" placeholder="••••••••">
                <p class="text-xs text-gray-500 mt-1">Minimum 12 characters, uppercase, lowercase, and number</p>
            </div>

            <div>
                <label class="label"><?= __('auth.password_confirmation') ?></label>
                <input type="password" name="password_confirmation" required class="input" placeholder="••••••••">
            </div>

            <button type="submit" class="w-full btn btn-primary">
                <?= __('auth.register') ?>
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <?= __('auth.already_have_account') ?>
            <a href="/login" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.login') ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
