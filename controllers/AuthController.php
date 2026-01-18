<?php

namespace Controllers;

use Core\Auth;
use Core\Flash;
use Core\Validator;
use Core\Session;
use Models\User;

class AuthController
{
    public function showRegister(): void
    {
        if (Auth::check()) {
            header('Location: ' . url('dashboard'));
            exit;
        }

        $this->disableCache();
        $this->view('auth/register', ['title' => __('auth.register')]);
    }

    public function register(): void
    {
        $fullName = trim((string) ($_POST['full_name'] ?? ''));
        $email = strtolower(trim((string) ($_POST['email'] ?? '')));
        $phone = trim((string) ($_POST['phone'] ?? ''));
        $password = (string) ($_POST['password'] ?? '');
        $passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

        $validator = new Validator([
            'full_name' => $fullName,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'full_name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|password',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->validate()) {
            $allErrors = $validator->errors(); // Get ALL errors
            Flash::error($validator->firstError());
            Session::set('validation_errors', json_encode($allErrors)); // For JS console logging
            header('Location: ' . url('register'));
            exit;
        }

        // Check password confirmation
        if ($password !== $passwordConfirmation) {
            Flash::error(__('auth.password_mismatch'));
            header('Location: ' . url('register'));
            exit;
        }

        if (User::emailExists($email)) {
            Flash::error(__('auth.email_already_registered') ?? 'This email is already registered. Please log in or reset your password.');
            header('Location: ' . url('login'));
            exit;
        }

        // Create user
        try {
            $userId = User::create([
                'full_name' => $fullName,
                'email' => $email,
                'phone' => $phone,
                'password_hash' => Auth::hashPassword($password),
                'role' => 'user',
                'locale' => Session::get('locale', 'en')
            ]);
        } catch (\Throwable $e) {
            $code = (string) $e->getCode();
            $message = $e->getMessage();
            $isDuplicate = $code === '23000'
                || stripos($message, 'duplicate') !== false
                || stripos($message, 'unique') !== false;
            if (function_exists('auth_log')) {
                auth_log('Register failed: email=' . $email . ' | code=' . $code . ' | error=' . $message);
            }
            if ($isDuplicate) {
                Flash::error(__('auth.email_already_registered') ?? 'This email is already registered. Please log in or reset your password.');
                header('Location: ' . url('login'));
                exit;
            }
            Flash::error(__('auth.register_failed') ?? 'Registration failed. Please try again.');
            header('Location: ' . url('register'));
            exit;
        }

        // Generate verification token
        $token = User::createVerificationToken($userId);

        // Send verification email
        $emailSent = \Core\Email::sendVerificationEmail(
            $_POST['email'],
            $token,
            $_POST['full_name']
        );

        if ($emailSent) {
            Flash::success(__('auth.register_success_check_email'));
        } else {
            Flash::warning(__('auth.register_success_but_email_failed'));
        }

        header('Location: ' . url('verify-info'));
        exit;
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: ' . url('dashboard'));
            exit;
        }

        $this->disableCache();
        $this->view('auth/login', ['title' => __('auth.login')]);
    }

    public function showVerifyInfo(): void
    {
        $this->disableCache();
        $this->view('auth/verify-info', ['title' => __('auth.verify_info_title') ?? 'Verify your email']);
    }

    public function login(): void
    {
        // Verify CSRF token
        if (!Session::verifyCsrfToken($_POST['_token'] ?? '')) {
            if (function_exists('auth_log')) {
                auth_log('Login CSRF failed');
            }
            Flash::error('Your session expired. Please refresh the page and try again.');
            header('Location: ' . url('login'));
            exit;
        }

        $validator = new Validator($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->validate()) {
            if (function_exists('auth_log')) {
                auth_log('Login validation failed: ' . ($validator->firstError() ?? 'unknown'));
            }
            $allErrors = $validator->errors();
            Flash::error($validator->firstError());
            Session::set('validation_errors', json_encode($allErrors)); // For JS console logging
            header('Location: ' . url('login'));
            exit;
        }

        $remember = isset($_POST['remember']) && $_POST['remember'] === '1';

        $user = User::findByEmail($_POST['email']);
        if (!$user) {
            if (function_exists('auth_log')) {
                auth_log('AuthController login failed: email not found');
            }
            Flash::error('No account found with that email address.');
            header('Location: ' . url('login'));
            exit;
        }

        $hash = $user['password_hash'] ?? '';
        $isBcrypt = is_string($hash) && (str_starts_with($hash, '$2y$') || str_starts_with($hash, '$2a$') || str_starts_with($hash, '$2b$'));
        if (!$isBcrypt) {
            if (function_exists('auth_log')) {
                auth_log('AuthController login failed: invalid hash format | user_id=' . $user['id']);
            }
            Flash::error('Account password is invalid. Please reset your password or contact support.');
            header('Location: ' . url('login'));
            exit;
        }

        if (!password_verify($_POST['password'], $hash)) {
            if (function_exists('auth_log')) {
                auth_log('AuthController login failed: invalid password');
            }
            Flash::error('Incorrect password. Use "Forgot password" to reset it.');
            header('Location: ' . url('login'));
            exit;
        }

        Auth::login($user, $remember);
        if (function_exists('auth_log')) {
            auth_log('AuthController login success: user_id=' . $user['id']);
        }
        Flash::success(__('auth.login_success'));

        // Redirect to intended page or dashboard
        $redirect = Session::get('intended_url', url('dashboard'));
        Session::remove('intended_url');

        header('Location: ' . $redirect);
        exit;
    }

    public function logout(): void
    {
        // CSRF token doğrulama
        if (!Session::verifyCsrfToken($_POST['_token'] ?? '')) {
            Flash::error('CSRF token validation failed. Please try again.');
            header('Location: ' . url(''));
            exit;
        }

        Auth::logout();
        Flash::success(__('auth.logout_success'));
        header('Location: ' . url(''));
        exit;
    }

    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            Flash::error(__('auth.invalid_token'));
            header('Location: ' . url(''));
            exit;
        }

        $user = User::findByVerificationToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_token'));
            header('Location: ' . url(''));
            exit;
        }

        // Check if token has expired
        if ($user['verification_expires'] && strtotime($user['verification_expires']) < time()) {
            Flash::error(__('auth.token_expired'));
            header('Location: ' . url('login'));
            exit;
        }

        User::verifyEmail($user['id']);

        Flash::success(__('auth.email_verified'));
        header('Location: ' . url('login'));
        exit;
    }

    public function me(): void
    {
        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');

        if (!Auth::check()) {
            http_response_code(401);
            echo json_encode(['authenticated' => false]);
            return;
        }

        $user = Auth::user();
        if (!$user) {
            http_response_code(401);
            echo json_encode(['authenticated' => false]);
            return;
        }

        echo json_encode([
            'authenticated' => true,
            'user' => [
                'id' => $user['id'] ?? null,
                'email' => $user['email'] ?? null,
                'full_name' => $user['full_name'] ?? null,
                'role' => $user['role'] ?? null,
                'email_verified' => (bool) ($user['email_verified'] ?? false),
                'phone_verified' => (bool) ($user['phone_verified'] ?? false),
            ],
        ]);
    }

    public function showForgotPassword(): void
    {
        if (function_exists('auth_log')) {
            auth_log('Show forgot-password form');
        }
        $this->disableCache();
        $this->view('auth/forgot-password', ['title' => __('auth.forgot_password')]);
    }

    public function forgotPassword(): void
    {
        if (function_exists('auth_log')) {
            auth_log('Forgot-password submit received');
        }
        $validator = new Validator($_POST, [
            'email' => 'required|email'
        ]);

        if (!$validator->validate()) {
            if (function_exists('auth_log')) {
                auth_log('Forgot-password validation failed: ' . ($validator->firstError() ?? 'unknown'));
            }
            Flash::error($validator->firstError());
            header('Location: ' . url('forgot-password'));
            exit;
        }

        $user = User::findByEmail($_POST['email']);

        if ($user) {
            $token = User::createResetToken($user['id']);

            // Fix: Remove locale from base URL to prevent double prefix
            $baseUrl = $_ENV['APP_URL'] ?? (isset($_SERVER['HTTP_HOST']) ? 'https://' . $_SERVER['HTTP_HOST'] : '');
            $baseUrl = rtrim($baseUrl, '/');
            $baseUrl = preg_replace('#/(en|et|ru)$#', '', $baseUrl);  // Remove locale if present

            $resetLink = $baseUrl . url('reset-password?token=' . $token);

            $subject = __('auth.reset_password') ?: 'Reset your password';
            $body = '<p>' . (__('auth.reset_instructions') ?? 'Click the link below to reset your password.') . '</p>';
            $body .= '<p><a href="' . $resetLink . '">' . $resetLink . '</a></p>';
            $body .= '<p>' . (__('auth.reset_ignore') ?? 'If you did not request this, you can ignore this email.') . '</p>';

            // Try to send email and check result
            $mailSent = send_mail($user['email'], $subject, $body, $_ENV['MAIL_REPLY_TO'] ?? null);

            if (!$mailSent) {
                // Log the failure
                error_log("Password reset email failed for: " . $user['email']);
                if (function_exists('auth_log')) {
                    auth_log('Forgot-password email failed: ' . $user['email']);
                }

                // Show error (don't reveal if email exists for security)
                Flash::error(__('auth.email_send_failed') ?? 'Failed to send reset email. Please contact support or try again later.');
                header('Location: ' . url('forgot-password'));
                exit;
            }
            if (function_exists('auth_log')) {
                auth_log('Forgot-password email sent: ' . $user['email']);
            }
        }

        // Always show success message to prevent email enumeration
        Flash::success(__('auth.reset_link_sent'));
        header('Location: ' . url('login'));
        exit;
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            Flash::error(__('auth.invalid_token'));
            header('Location: ' . url('login'));
            exit;
        }

        $user = User::findByResetToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_or_expired_token'));
            header('Location: ' . url('login'));
            exit;
        }

        $this->disableCache();
        $this->view('auth/reset-password', [
            'title' => __('auth.reset_password'),
            'token' => $token
        ]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';

        if (function_exists('auth_log')) {
            auth_log('Reset-password submit received | token_present=' . (int) !empty($token));
        }
        $validator = new Validator($_POST, [
            'password' => 'required|password',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            if (function_exists('auth_log')) {
                auth_log('Reset-password validation failed: ' . ($validator->firstError() ?? 'unknown'));
            }
            header('Location: ' . url('reset-password?token=' . $token));
            exit;
        }

        if ($_POST['password'] !== $_POST['password_confirmation']) {
            Flash::error(__('auth.password_mismatch'));
            if (function_exists('auth_log')) {
                auth_log('Reset-password mismatch');
            }
            header('Location: ' . url('reset-password?token=' . $token));
            exit;
        }

        $user = User::findByResetToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_or_expired_token'));
            if (function_exists('auth_log')) {
                auth_log('Reset-password token invalid or expired');
            }
            header('Location: ' . url('login'));
            exit;
        }

        $updatedRows = User::resetPassword($user['id'], $_POST['password']);
        Session::remove('intended_url');
        if (function_exists('auth_log')) {
            $freshUser = User::find($user['id']);
            $hash = $freshUser['password_hash'] ?? '';
            $hashLen = $hash ? strlen($hash) : 0;
            $hashPrefix = $hash ? substr($hash, 0, 7) : 'none';
            auth_log('Password reset success: user_id=' . $user['id'] . ' | rows=' . $updatedRows . ' | hash_len=' . $hashLen . ' | hash_prefix=' . $hashPrefix);
        }

        Flash::success(__('auth.password_reset_success'));
        header('Location: ' . url('login'));
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }

    private function disableCache(): void
    {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');
        header('Expires: 0');
    }
}
