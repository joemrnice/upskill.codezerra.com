<?php
/**
 * Router Helper Class
 * Centralized routing helper for consistent URL generation and route validation
 */

class Router {
    private static $baseUrl;
    
    /**
     * Initialize router
     */
    public static function init() {
        self::$baseUrl = defined('BASE_URL') ? BASE_URL : '';
    }
    
    /**
     * Generate URL for a route
     */
    public static function url($path = '') {
        if (empty(self::$baseUrl)) {
            self::init();
        }
        
        return rtrim(self::$baseUrl, '/') . '/' . ltrim($path, '/');
    }
    
    /**
     * Redirect to a URL
     */
    public static function redirect($path, $statusCode = 302) {
        $url = self::url($path);
        header("Location: $url", true, $statusCode);
        exit;
    }
    
    /**
     * Check if a controller exists
     */
    public static function controllerExists($controllerName) {
        // Sanitize controller name to prevent directory traversal
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controllerName)) {
            return false;
        }
        
        $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
        return file_exists($controllerFile);
    }
    
    /**
     * Check if a controller method exists
     * Note: This method loads the controller file to check method existence
     */
    public static function methodExists($controllerName, $methodName) {
        // Sanitize controller name (defense in depth)
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controllerName)) {
            return false;
        }
        
        if (!self::controllerExists($controllerName)) {
            return false;
        }
        
        require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
        return method_exists($controllerName, $methodName);
    }
    
    /**
     * Load and instantiate a controller
     */
    public static function loadController($controllerName) {
        // Sanitize controller name to prevent directory traversal
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $controllerName)) {
            throw new Exception("Invalid controller name: $controllerName");
        }
        
        if (!self::controllerExists($controllerName)) {
            throw new Exception("Controller not found: $controllerName");
        }
        
        require_once __DIR__ . '/../controllers/' . $controllerName . '.php';
        return new $controllerName();
    }
    
    /**
     * Execute controller method with error handling
     */
    public static function executeController($controllerName, $methodName = 'index') {
        try {
            // Validate controller exists
            if (!self::controllerExists($controllerName)) {
                ErrorHandler::show404Error();
                // ErrorHandler::show404Error() calls exit, so code below won't execute
            }
            
            // Load controller
            $controller = self::loadController($controllerName);
            
            // Validate method exists
            if (!method_exists($controller, $methodName)) {
                ErrorHandler::show404Error();
                // ErrorHandler::show404Error() calls exit, so code below won't execute
            }
            
            // Execute method
            $controller->$methodName();
            
        } catch (Exception $e) {
            // Log the error
            error_log("Controller execution error: " . $e->getMessage());
            
            // Show appropriate error page
            if (strpos($e->getMessage(), 'Database') !== false || $e instanceof PDOException) {
                ErrorHandler::showDatabaseError();
            } else {
                ErrorHandler::show500Error();
            }
            // ErrorHandler methods call exit, so code below won't execute
        }
    }
    
    /**
     * Get current URL path
     */
    public static function getCurrentPath() {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }
    
    /**
     * Get current request method
     */
    public static function getRequestMethod() {
        return $_SERVER['REQUEST_METHOD'];
    }
    
    /**
     * Check if current request is POST
     */
    public static function isPost() {
        return self::getRequestMethod() === 'POST';
    }
    
    /**
     * Check if current request is GET
     */
    public static function isGet() {
        return self::getRequestMethod() === 'GET';
    }
    
    /**
     * Sanitize URL path
     */
    public static function sanitizePath($path) {
        // Remove multiple slashes
        $path = preg_replace('#/+#', '/', $path);
        
        // Remove query string
        $path = strtok($path, '?');
        
        // Remove trailing slash except for root
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        
        return $path;
    }
    
    /**
     * Parse route pattern
     */
    public static function matchRoute($pattern, $path) {
        // Exact match
        if ($pattern === $path) {
            return [];
        }
        
        // Convert route pattern to regex
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $pattern);
        $pattern = '#^' . $pattern . '$#';
        
        // Check if path matches pattern
        if (preg_match($pattern, $path, $matches)) {
            array_shift($matches); // Remove full match
            return $matches;
        }
        
        return false;
    }
}
