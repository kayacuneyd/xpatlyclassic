<?php require __DIR__ . '/../layouts/header.php'; ?>

<div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-lg p-8">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900"><?= __('auth.register') ?></h2>
            <p class="mt-2 text-gray-600">Create your free account</p>
        </div>

        <form method="POST" action="<?= url('register') ?>" class="space-y-6">
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

                <!-- Help text explaining why we need strong passwords -->
                <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="text-sm text-blue-800">
                            <strong>Why strong passwords?</strong> We require secure passwords to protect your account and personal data from unauthorized access.
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <input type="password"
                           name="password"
                           id="password"
                           required
                           class="input pr-10"
                           placeholder="Enter a secure password"
                           minlength="12">
                    <button type="button" id="toggle-register-password"
                            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                            aria-label="Show password">
                        👁️
                    </button>
                </div>

                <!-- Password requirements checklist -->
                <div class="mt-3 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                    <p class="text-sm font-semibold text-gray-900 mb-2">Your password must have:</p>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start gap-2" data-rule="length">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>At least 12 characters</strong>
                                <span class="text-gray-500">- longer is better!</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2" data-rule="upper">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>One uppercase letter</strong>
                                <span class="text-gray-500">(A-Z)</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2" data-rule="lower">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>One lowercase letter</strong>
                                <span class="text-gray-500">(a-z)</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2" data-rule="number">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>One number</strong>
                                <span class="text-gray-500">(0-9)</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2" data-rule="sequence">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>No obvious sequences</strong>
                                <span class="text-gray-500">like 1234, 5678, or abcd</span>
                            </span>
                        </li>
                        <li class="flex items-start gap-2" data-rule="repeat">
                            <span class="text-gray-400 mt-0.5 rule-icon">•</span>
                            <span class="text-gray-700">
                                <strong>No repeated characters</strong>
                                <span class="text-gray-500">like aaa or 111</span>
                            </span>
                        </li>
                    </ul>

                    <!-- Good examples -->
                    <div class="mt-3 pt-3 border-t border-gray-300">
                        <p class="text-xs font-semibold text-green-700 mb-1">✓ Good examples:</p>
                        <div class="flex flex-wrap gap-1.5">
                            <code class="text-xs bg-green-50 text-green-800 px-2 py-0.5 rounded">MySecure@Pass2024</code>
                            <code class="text-xs bg-green-50 text-green-800 px-2 py-0.5 rounded">DonaldTrump1579</code>
                            <code class="text-xs bg-green-50 text-green-800 px-2 py-0.5 rounded">TestUser!2026</code>
                        </div>
                    </div>

                    <!-- Bad examples -->
                    <div class="mt-2">
                        <p class="text-xs font-semibold text-red-700 mb-1">✗ Avoid these:</p>
                        <div class="flex flex-wrap gap-1.5">
                            <code class="text-xs bg-red-50 text-red-800 px-2 py-0.5 rounded line-through">Password1234</code>
                            <code class="text-xs bg-red-50 text-red-800 px-2 py-0.5 rounded line-through">Test123</code>
                            <code class="text-xs bg-red-50 text-red-800 px-2 py-0.5 rounded line-through">Aabbcc22</code>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">(Too short, obvious patterns, or repeated chars)</p>
                    </div>
                </div>
            </div>

            <div>
                <label class="label"><?= __('auth.password_confirmation') ?></label>
                <div class="relative">
                    <input type="password" name="password_confirmation" required class="input pr-10"
                           id="password-confirmation" placeholder="••••••••">
                    <button type="button" id="toggle-register-confirmation"
                            class="absolute inset-y-0 right-0 px-3 text-gray-500 hover:text-gray-700"
                            aria-label="Show password confirmation">
                        👁️
                    </button>
                </div>
            </div>

            <button type="submit" class="w-full btn btn-primary">
                <?= __('auth.register') ?>
            </button>
        </form>

        <div class="mt-6 text-center text-sm text-gray-600">
            <?= __('auth.already_have_account') ?>
            <a href="<?= url('login') ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                <?= __('auth.login') ?>
            </a>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../layouts/footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const confirmationInput = document.getElementById('password-confirmation');
    const togglePassword = document.getElementById('toggle-register-password');
    const toggleConfirmation = document.getElementById('toggle-register-confirmation');
    const rules = document.querySelectorAll('[data-rule]');

    function setToggleState(input, toggle) {
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';
        toggle.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
        toggle.textContent = isHidden ? '🙈' : '👁️';
    }

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            setToggleState(passwordInput, togglePassword);
        });
    }

    if (toggleConfirmation && confirmationInput) {
        toggleConfirmation.addEventListener('click', function() {
            setToggleState(confirmationInput, toggleConfirmation);
        });
    }

    function hasSequentialChars(value) {
        const str = value.toLowerCase();
        const length = 4;
        for (let i = 0; i <= str.length - length; i++) {
            let asc = true;
            let desc = true;
            for (let j = 0; j < length - 1; j++) {
                const current = str.charCodeAt(i + j);
                const next = str.charCodeAt(i + j + 1);
                if (next !== current + 1) asc = false;
                if (next !== current - 1) desc = false;
            }
            if (asc || desc) {
                return true;
            }
        }
        return false;
    }

    function updateRules(value) {
        const checks = {
            length: value.length >= 12,
            upper: /[A-Z]/.test(value),
            lower: /[a-z]/.test(value),
            number: /[0-9]/.test(value),
            sequence: !hasSequentialChars(value),
            repeat: !/(.)\1\1/.test(value)
        };

        rules.forEach(function(rule) {
            const key = rule.getAttribute('data-rule');
            const passed = checks[key];
            const icon = rule.querySelector('.rule-icon');
            rule.classList.toggle('text-green-700', passed);
            rule.classList.toggle('text-gray-700', !passed);
            if (icon) {
                icon.textContent = passed ? '✓' : '•';
                icon.classList.toggle('text-green-600', passed);
                icon.classList.toggle('text-gray-400', !passed);
            }
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            updateRules(passwordInput.value);
        });
        updateRules(passwordInput.value);
    }
});
</script>
