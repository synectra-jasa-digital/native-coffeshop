<?php
// Single Entry Point

// 1. Load Configuration
require_once __DIR__ . '/../config/config.php';

// 2. Load Autoloader
require_once __DIR__ . '/autoloader.php';

// 3. Initialize Session
\App\Core\Session::init();

// 4. Load Routes
$router = require_once __DIR__ . '/../routes/web.php';

// 5. Dispatch Request
$method = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Handle subdirectories if the app is not running at the server root
$basePath = parse_url(BASE_URL, PHP_URL_PATH) ?? '';

if (!empty($basePath) && $basePath !== '/') {
    // Strip base path from URI
    if (strpos($uri, $basePath) === 0) {
        $uri = substr($uri, strlen($basePath));
    }
}

// Remove query strings
$uri = strtok($uri, '?');

// Remove trailing slash except if it's the root
if ($uri !== '/' && str_ends_with($uri, '/')) {
    $uri = rtrim($uri, '/');
}

// Default to '/' if URI is empty after stripping
if (empty($uri)) {
    $uri = '/';
}

$router->dispatch($method, $uri);
