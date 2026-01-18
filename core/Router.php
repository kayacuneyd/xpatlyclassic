<?php

namespace Core;

class Router
{
    private array $routes = [];
    private string $locale = 'en';
    private array $supportedLocales = ['en', 'et', 'ru'];

    public function __construct()
    {
        // Extract locale immediately upon construction
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $this->extractLocale($uri);
    }


    public function get(string $path, callable|array $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|array $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable|array $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable|array $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable|array $handler): void
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'handler' => $handler
        ];
    }

    public function resolve(): mixed
    {
        $method = $_SERVER['REQUEST_METHOD'];
        // Treat HEAD like GET for routing so uptime checks don't 404
        $routeMethod = $method === 'HEAD' ? 'GET' : $method;
        $uri = $_SERVER['REQUEST_URI'];

        // Validate CSRF token for state-changing requests
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $this->validateCsrfToken();
        }

        // Remove query string
        $uri = parse_url($uri, PHP_URL_PATH);

        // Extract and remove locale from URI
        $this->extractLocale($uri);

        // Remove trailing slash except for root
        if ($uri !== '/' && str_ends_with($uri, '/')) {
            $uri = rtrim($uri, '/');
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $routeMethod) {
                continue;
            }

            $pattern = $this->convertToPattern($route['path']);

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match

                return $this->callHandler($route['handler'], $matches);
            }
        }

        // No route found
        http_response_code(404);
        require __DIR__ . '/../views/errors/404.php';
        exit;
    }

    private function validateCsrfToken(): void
    {
        $honeypotField = Session::getHoneypotFieldName();
        $honeypot = $_POST[$honeypotField] ?? '';
        if (is_string($honeypot) && trim($honeypot) !== '') {
            if (function_exists('auth_log')) {
                auth_log('Honeypot triggered: path=' . ($_SERVER['REQUEST_URI'] ?? ''));
            }
            http_response_code(403);
            Flash::error('Invalid request. Please try again.');

            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                echo '<h1>403 Forbidden</h1><p>Invalid request.</p>';
            }
            exit;
        }

        $token = $_POST['_token'] ?? $_POST['csrf_token'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;

        if (!$token || !Session::verifyCsrfToken($token)) {
            if (function_exists('auth_log')) {
                $tokenHash = $token ? substr(hash('sha256', $token), 0, 10) : 'none';
                $cookieHash = !empty($_COOKIE['csrf_token']) ? substr(hash('sha256', $_COOKIE['csrf_token']), 0, 10) : 'none';
                $sessionToken = Session::get('csrf_token', '');
                $sessionHash = $sessionToken ? substr(hash('sha256', $sessionToken), 0, 10) : 'none';
                auth_log('CSRF failed: path=' . ($_SERVER['REQUEST_URI'] ?? '') . ' | has_token=' . (int) (bool) $token . ' | token=' . $tokenHash . ' | cookie=' . $cookieHash . ' | session=' . $sessionHash . ' | sid=' . session_id());
            }
            http_response_code(403);
            Flash::error('CSRF token validation failed. Please try again.');

            // Redirect back or show error page
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location: ' . $_SERVER['HTTP_REFERER']);
            } else {
                echo '<h1>403 Forbidden</h1><p>CSRF token validation failed.</p>';
            }
            exit;
        }
    }

    private function extractLocale(string &$uri): void
    {
        // Strip one or more leading locale segments (handles accidental double prefixes like /ru/et/...)
        $foundLocale = null;
        $remaining = $uri;

        while (preg_match('#^/(' . implode('|', $this->supportedLocales) . ')(/|$)#', $remaining, $matches)) {
            $foundLocale = $matches[1];
            $remaining = substr($remaining, strlen('/' . $foundLocale));
            if ($remaining === '') {
                $remaining = '/';
                break;
            }
        }

        if ($foundLocale) {
            $this->locale = $foundLocale;
            $_SESSION['locale'] = $this->locale;
            $uri = $remaining;
        } else {
            // Use session locale or default
            $this->locale = $_SESSION['locale'] ?? 'en';
        }
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    private function convertToPattern(string $path): string
    {
        // Convert route placeholders to regex
        // Example: /listings/{id} becomes /listings/([^/]+)
        $pattern = preg_replace('#\{([a-zA-Z0-9_]+)\}#', '([^/]+)', $path);
        return '#^' . $pattern . '$#';
    }

    private function callHandler(callable|array $handler, array $params): mixed
    {
        if (is_callable($handler)) {
            return call_user_func_array($handler, $params);
        }

        if (is_array($handler)) {
            [$controller, $method] = $handler;

            if (is_string($controller)) {
                $controller = new $controller();
            }

            return call_user_func_array([$controller, $method], $params);
        }

        throw new \Exception('Invalid route handler');
    }

    public function redirect(string $path, int $code = 302): void
    {
        // Add locale prefix if not present
        if (!preg_match('#^/(' . implode('|', $this->supportedLocales) . ')/#', $path)) {
            $path = '/' . $this->locale . $path;
        }

        header("Location: $path", true, $code);
        exit;
    }

    public function url(string $path): string
    {
        // Generate URL with current locale
        if (!preg_match('#^/(' . implode('|', $this->supportedLocales) . ')/#', $path)) {
            $path = '/' . $this->locale . $path;
        }

        return $path;
    }
}
