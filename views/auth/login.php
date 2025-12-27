<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900"><?= __('auth.login') ?></h2>
            <p class="mt-2 text-gray-600">Welcome back to Xpatly</p>
        </div>

        <form method="POST" action="/login" class="space-y-6">
            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

            <div>
                <label class="label"><?= __('auth.email') ?></label>
                <input type="email" name="email" required class="input" placeholder="your@email.com">
            </div>

            <div>
                <label class="label"><?= __('auth.password') ?></label>
                <input type="password" name="password" required class="input" placeholder="••••••••">
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" value="1" id="remember" class="h-4 w-4 text-primary-600 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-700"><?= __('auth.remember_me') ?></label>
                </div>
                <a href="/forgot-password" class="text-sm text-primary-600 hover:text-primary-700">
                    <?= __('auth.forgot_password') ?>
                </a>
            </div>

            <button type="submit" class="w-full btn btn-primary">
                <?= __('auth.login') ?>
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <?= __('auth.dont_have_account') ?>
            <a href="/register" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.register') ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
