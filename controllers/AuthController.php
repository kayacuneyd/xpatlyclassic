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
            header('Location: /dashboard');
            exit;
        }

        $this->view('auth/register', ['title' => __('auth.register')]);
    }

    public function register(): void
    {
        $validator = new Validator($_POST, [
            'full_name' => 'required|min:3|max:100',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'password' => 'required|password',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /register');
            exit;
        }

        // Check password confirmation
        if ($_POST['password'] !== $_POST['password_confirmation']) {
            Flash::error(__('auth.password_mismatch'));
            header('Location: /register');
            exit;
        }

        // Create user
        $userId = User::create([
            'full_name' => $_POST['full_name'],
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'password_hash' => Auth::hashPassword($_POST['password']),
            'role' => 'user',
            'locale' => Session::get('locale', 'en')
        ]);

        // Generate verification token
        $token = User::createVerificationToken($userId);

        // Send verification email (implement email sending)
        // TODO: Send email with verification link

        Flash::success(__('auth.registration_success'));
        header('Location: /login');
        exit;
    }

    public function showLogin(): void
    {
        if (Auth::check()) {
            header('Location: /dashboard');
            exit;
        }

        $this->view('auth/login', ['title' => __('auth.login')]);
    }

    public function login(): void
    {
        // Verify CSRF token
        if (!Session::verifyCsrfToken($_POST['_token'] ?? '')) {
            Flash::error('Invalid request');
            header('Location: /login');
            exit;
        }

        $validator = new Validator($_POST, [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /login');
            exit;
        }

        $remember = isset($_POST['remember']) && $_POST['remember'] === '1';

        if (Auth::attempt($_POST['email'], $_POST['password'], $remember)) {
            Flash::success(__('auth.login_success'));

            // Redirect to intended page or dashboard
            $redirect = Session::get('intended_url', '/dashboard');
            Session::remove('intended_url');

            header('Location: ' . $redirect);
            exit;
        }

        Flash::error(__('auth.invalid_credentials'));
        header('Location: /login');
        exit;
    }

    public function logout(): void
    {
        Auth::logout();
        Flash::success(__('auth.logout_success'));
        header('Location: /');
        exit;
    }

    public function verifyEmail(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            Flash::error(__('auth.invalid_token'));
            header('Location: /');
            exit;
        }

        $user = User::findByVerificationToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_token'));
            header('Location: /');
            exit;
        }

        User::verifyEmail($user['id']);

        Flash::success(__('auth.email_verified'));
        header('Location: /login');
        exit;
    }

    public function showForgotPassword(): void
    {
        $this->view('auth/forgot-password', ['title' => __('auth.forgot_password')]);
    }

    public function forgotPassword(): void
    {
        $validator = new Validator($_POST, [
            'email' => 'required|email'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /forgot-password');
            exit;
        }

        $user = User::findByEmail($_POST['email']);

        if ($user) {
            $token = User::createResetToken($user['id']);
            // TODO: Send password reset email
        }

        // Always show success message to prevent email enumeration
        Flash::success(__('auth.reset_link_sent'));
        header('Location: /login');
        exit;
    }

    public function showResetPassword(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            Flash::error(__('auth.invalid_token'));
            header('Location: /login');
            exit;
        }

        $user = User::findByResetToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_or_expired_token'));
            header('Location: /login');
            exit;
        }

        $this->view('auth/reset-password', [
            'title' => __('auth.reset_password'),
            'token' => $token
        ]);
    }

    public function resetPassword(): void
    {
        $token = $_POST['token'] ?? '';

        $validator = new Validator($_POST, [
            'password' => 'required|password',
            'password_confirmation' => 'required'
        ]);

        if (!$validator->validate()) {
            Flash::error($validator->firstError());
            header('Location: /reset-password?token=' . $token);
            exit;
        }

        if ($_POST['password'] !== $_POST['password_confirmation']) {
            Flash::error(__('auth.password_mismatch'));
            header('Location: /reset-password?token=' . $token);
            exit;
        }

        $user = User::findByResetToken($token);

        if (!$user) {
            Flash::error(__('auth.invalid_or_expired_token'));
            header('Location: /login');
            exit;
        }

        User::resetPassword($user['id'], $_POST['password']);

        Flash::success(__('auth.password_reset_success'));
        header('Location: /login');
        exit;
    }

    private function view(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . '/../views/' . $view . '.php';
    }
}
