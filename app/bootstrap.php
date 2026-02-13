<?php
/**
 * Bootstrap File
 * Initialize application and autoload classes
 */

// Load ErrorHandler first (before any errors can occur)
require_once __DIR__ . '/../app/helpers/ErrorHandler.php';

// Initialize error handling
ErrorHandler::init();

// Start session
require_once __DIR__ . '/../app/helpers/Session.php';
Session::start();

// Autoload classes
spl_autoload_register(function ($className) {
    $paths = [
        __DIR__ . '/../app/models/',
        __DIR__ . '/../app/controllers/',
        __DIR__ . '/../app/helpers/',
    ];
    
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Load configuration with error handling
try {
    $config = require __DIR__ . '/../config/config.php';
    define('BASE_URL', $config['site_url']);
    define('SITE_NAME', $config['site_name']);
    
    // Define additional config constants
    if (isset($config['admin_email'])) {
        define('ADMIN_EMAIL', $config['admin_email']);
    }
} catch (Exception $e) {
    error_log("Configuration loading error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

// Helper functions
function redirect($url) {
    header("Location: " . $url);
    exit;
}

function base_url($path = '') {
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function asset_url($path = '') {
    return base_url('public/' . ltrim($path, '/'));
}

function view($viewPath, $data = []) {
    extract($data);
    $viewFile = __DIR__ . '/../app/views/' . $viewPath . '.php';
    
    if (file_exists($viewFile)) {
        require_once $viewFile;
    } else {
        // Log with view path name only (not full path) to prevent disclosure
        error_log("View not found: " . basename($viewPath));
        ErrorHandler::show500Error();
        // ErrorHandler::show500Error() calls exit, execution stops here
    }
}

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function old($key, $default = '') {
    return $_POST[$key] ?? $default;
}

function csrf_token() {
    return Session::generateToken();
}

function csrf_field() {
    $token = csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
