<?php

// Serve static files directly in PHP built-in server
if (php_sapi_name() === 'cli-server') {
    $file = __DIR__ . $_SERVER['REQUEST_URI'];
    if (is_file($file)) {
        return false; // Serve the file directly
    }
}

// Load environment variables
if (file_exists(__DIR__ . '/../.env')) {
    $lines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

// Environment
$appEnv = $_ENV['APP_ENV'] ?? getenv('APP_ENV') ?? 'production';

// Error reporting (disable display in production)
error_reporting(E_ALL);
if ($appEnv === 'production') {
    ini_set('display_errors', 0);
} else {
    ini_set('display_errors', 1);
}

// Load Composer autoloader
require __DIR__ . '/../vendor/autoload.php';

// Load helper functions
require __DIR__ . '/../core/helpers.php';

// Basic security headers
header('X-Frame-Options: SAMEORIGIN');
header('X-Content-Type-Options: nosniff');
header('Referrer-Policy: strict-origin-when-cross-origin');
header('Permissions-Policy: geolocation=(), microphone=(), camera=()');

use Core\Router;
use Core\Session;
use Core\Translation;
use Controllers\HomeController;
use Controllers\ListingController;
use Controllers\SearchController;
use Controllers\AuthController;
use Controllers\UserController;
use Controllers\MessageController;
use Controllers\AdminController;
use Controllers\PageController;
use Controllers\BlogController;
use Controllers\SitemapController;

// Initialize session
Session::start();

// Initialize router
$router = new Router();

// Load translations (locale is now available)
Translation::setLocale($router->getLocale());

// Public routes
$router->get('/', [HomeController::class, 'index']);
$router->get('/sitemap.xml', [SitemapController::class, 'index']);
$router->get('/listings', [SearchController::class, 'index']);
$router->get('/listings/create', [ListingController::class, 'create']);
$router->post('/listings/create', [ListingController::class, 'store']);
$router->get('/listings/{id}', [ListingController::class, 'show']);
$router->post('/listings/{id}/message', [MessageController::class, 'send']);
$router->post('/listings/{id}/report', [ListingController::class, 'report']);

// Static pages
$router->get('/about', [PageController::class, 'about']);
$router->get('/imprint', [PageController::class, 'imprint']);
$router->get('/privacy', [PageController::class, 'privacy']);
$router->get('/terms', [PageController::class, 'terms']);
$router->get('/contact', [PageController::class, 'contact']);
$router->get('/faq', [PageController::class, 'faq']);

// Blog routes
$router->get('/blog', [BlogController::class, 'index']);
$router->get('/blog/{slug}', [BlogController::class, 'show']);
$router->post('/blog/{slug}/comment', [BlogController::class, 'addComment']);

// Auth routes
$router->get('/register', [AuthController::class, 'showRegister']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/verify-info', [AuthController::class, 'showVerifyInfo']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->post('/logout', [AuthController::class, 'logout']);
$router->get('/verify-email', [AuthController::class, 'verifyEmail']);
$router->get('/me', [AuthController::class, 'me']);
$router->get('/forgot-password', [AuthController::class, 'showForgotPassword']);
$router->post('/forgot-password', [AuthController::class, 'forgotPassword']);
$router->get('/reset-password', [AuthController::class, 'showResetPassword']);
$router->post('/reset-password', [AuthController::class, 'resetPassword']);

// User dashboard routes
$router->get('/dashboard', [UserController::class, 'dashboard']);
$router->get('/my-listings', [ListingController::class, 'myListings']);
$router->get('/favorites', [UserController::class, 'favorites']);
$router->post('/user/role', [UserController::class, 'updateRole']);
$router->post('/favorites/{id}/toggle', [UserController::class, 'toggleFavorite']);
$router->get('/messages', [MessageController::class, 'index']);
$router->get('/messages/conversation/{id}', [MessageController::class, 'viewConversation']);
$router->post('/messages/conversation/{id}/reply', [MessageController::class, 'reply']);

// Listing management routes (edit/delete must come after create)
$router->get('/listings/{id}/edit', [ListingController::class, 'edit']);
$router->post('/listings/{id}/edit', [ListingController::class, 'update']);
$router->post('/listings/{id}/delete', [ListingController::class, 'delete']);
$router->post('/listings/{id}/toggle-availability', [ListingController::class, 'toggleAvailability']);

// Admin routes
$router->get('/admin', [AdminController::class, 'dashboard']);
$router->get('/admin/listings', [AdminController::class, 'listings']);
$router->get('/admin/listings/{id}/edit', [AdminController::class, 'editListing']);
$router->post('/admin/listings/{id}/edit', [AdminController::class, 'updateListing']);
$router->post('/admin/listings/{id}/delete', [AdminController::class, 'deleteListing']);
$router->post('/admin/listings/{id}/status', [AdminController::class, 'changeStatus']);
$router->get('/admin/users', [AdminController::class, 'users']);
$router->get('/admin/users/{id}/edit', [AdminController::class, 'editUser']);
$router->post('/admin/users/{id}/edit', [AdminController::class, 'updateUser']);
$router->post('/admin/users/{id}/delete', [AdminController::class, 'deleteUser']);
$router->get('/admin/reports', [AdminController::class, 'reports']);
$router->post('/admin/reports/{id}/review', [AdminController::class, 'reviewReport']);
$router->get('/admin/logs', [AdminController::class, 'logs']);
$router->get('/admin/settings', [AdminController::class, 'settings']);
$router->post('/admin/settings', [AdminController::class, 'updateSettings']);
$router->post('/admin/transfer-ownership', [AdminController::class, 'transferOwnership']);

// Admin Blog routes
$router->get('/admin/blog', [BlogController::class, 'adminIndex']);
$router->get('/admin/blog/create', [BlogController::class, 'create']);
$router->post('/admin/blog', [BlogController::class, 'store']);
$router->get('/admin/blog/{id}/edit', [BlogController::class, 'edit']);
$router->post('/admin/blog/{id}', [BlogController::class, 'update']);
$router->post('/admin/blog/{id}/delete', [BlogController::class, 'delete']);

// Resolve route
try {
    $router->resolve();
} catch (Exception $e) {
    http_response_code(500);
    echo "Error: " . $e->getMessage();
}
