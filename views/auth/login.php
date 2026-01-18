<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900"><?= __('auth.login') ?></h2>
            <p class="mt-2 text-gray-600">Welcome back to Xpatly</p>
        </div>

        <form method="POST" action="<?= url('login') ?>" class="space-y-6">
            <input type="hidden" name="_token" value="<?= Core\Session::getCsrfToken() ?>">

            <div>
                <label class="label"><?= __('auth.email') ?></label>
                <input type="email" name="email" required class="input" placeholder="your@email.com">
            </div>

            <div>
                <label class="label"><?= __('auth.password') ?></label>
                <div class="relative">
                    <input id="login-password" type="password" name="password" required class="input pr-10" placeholder="••••••••">
                    <button type="button" id="toggle-login-password"
                            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                            aria-label="Show password">
                        👁️
                    </button>
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input type="checkbox" name="remember" value="1" id="remember" class="h-4 w-4 text-primary-600 rounded">
                    <label for="remember" class="ml-2 text-sm text-gray-700"><?= __('auth.remember_me') ?></label>
                </div>
            </div>

            <button type="submit" class="w-full btn btn-primary">
                <?= __('auth.login') ?>
            </button>
        </form>

        <div class="mt-4 text-center text-sm text-gray-600">
            <a href="<?= url('forgot-password') ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.forgot_password') ?>
            </a>
        </div>

        <div class="mt-4 text-center text-sm text-gray-600">
            <?= __('auth.dont_have_account') ?>
            <a href="<?= url('register') ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.register') ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('login-password');
    const toggle = document.getElementById('toggle-login-password');
    if (!input || !toggle) return;

    toggle.addEventListener('click', function() {
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        toggle.textContent = isHidden ? '🙈' : '👁️';
    });
});
</script>
