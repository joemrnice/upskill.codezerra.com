<?php
/**
 * Error Handler Class
 * Centralized error handling for the application
 */

class ErrorHandler {
    private static $logPath;
    private static $isProduction;
    
    /**
     * Initialize the error handler
     */
    public static function init() {
        // Set log path
        self::$logPath = __DIR__ . '/../../logs/';
        
        // Detect environment (production if APP_ENV is set to 'production')
        self::$isProduction = (getenv('APP_ENV') === 'production');
        
        // Create logs directory if it doesn't exist
        if (!is_dir(self::$logPath)) {
            if (!mkdir(self::$logPath, 0755, true)) {
                // Log failure without exposing full path
                error_log("Failed to create logs directory");
            }
        }
        
        // Set error and exception handlers
        set_error_handler([self::class, 'handleError']);
        set_exception_handler([self::class, 'handleException']);
        register_shutdown_function([self::class, 'handleShutdown']);
        
        // Configure PHP error reporting
        if (self::$isProduction) {
            error_reporting(E_ALL);
            ini_set('display_errors', '0');
            ini_set('log_errors', '1');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        }
    }
    
    /**
     * Handle PHP errors
     */
    public static function handleError($severity, $message, $file, $line) {
        // Don't handle errors that are suppressed with @
        if (!(error_reporting() & $severity)) {
            return;
        }
        
        // Log the error
        self::logError('PHP Error', [
            'severity' => $severity,
            'message' => $message,
            'file' => $file,
            'line' => $line
        ]);
        
        // Convert error to exception for fatal errors
        if ($severity === E_ERROR || $severity === E_USER_ERROR) {
            throw new ErrorException($message, 0, $severity, $file, $line);
        }
        
        // In development, let PHP handle the error normally
        return !self::$isProduction;
    }
    
    /**
     * Handle uncaught exceptions
     */
    public static function handleException($exception) {
        // Log the exception
        self::logError('Uncaught Exception', [
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString()
        ]);
        
        // Show appropriate error page
        if (self::isDatabaseException($exception)) {
            self::showDatabaseError();
        } else {
            self::show500Error();
        }
    }
    
    /**
     * Handle fatal errors during shutdown
     */
    public static function handleShutdown() {
        $error = error_get_last();
        
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
            self::logError('Fatal Error', $error);
            
            // Clear output buffer to prevent partial page rendering
            if (ob_get_level()) {
                ob_end_clean();
            }
            
            self::show500Error();
        }
    }
    
    /**
     * Log error to file
     */
    private static function logError($type, $details) {
        $timestamp = date('Y-m-d H:i:s');
        $logFile = self::$logPath . 'error-' . date('Y-m-d') . '.log';
        
        $logMessage = sprintf(
            "[%s] %s\n%s\n%s\n",
            $timestamp,
            $type,
            json_encode($details, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
            str_repeat('-', 80)
        );
        
        // Try to write to file, fallback to PHP error log if it fails
        if (!error_log($logMessage, 3, $logFile)) {
            error_log("Failed to write to log file. " . $type . ": " . json_encode($details));
        }
    }
    
    /**
     * Check if exception is database-related
     */
    private static function isDatabaseException($exception) {
        return $exception instanceof PDOException || 
               strpos($exception->getMessage(), 'Database') !== false ||
               strpos($exception->getMessage(), 'database') !== false ||
               strpos($exception->getMessage(), 'Connection') !== false;
    }
    
    /**
     * Show 404 error page
     */
    public static function show404Error() {
        http_response_code(404);
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Load 404 view
        $viewFile = __DIR__ . '/../views/errors/404.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Fallback if view doesn't exist
            echo "<!DOCTYPE html>
<html>
<head>
    <title>404 - Page Not Found</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 48px; color: #333; }
        p { color: #666; }
    </style>
</head>
<body>
    <h1>404 - Page Not Found</h1>
    <p>The page you're looking for doesn't exist.</p>
    <a href='/public/index.php'>Go Home</a>
</body>
</html>";
        }
        exit;
    }
    
    /**
     * Show 500 error page
     */
    public static function show500Error() {
        http_response_code(500);
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Load 500 view
        $viewFile = __DIR__ . '/../views/errors/500.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Fallback if view doesn't exist
            echo "<!DOCTYPE html>
<html>
<head>
    <title>500 - Server Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 48px; color: #d32f2f; }
        p { color: #666; }
    </style>
</head>
<body>
    <h1>500 - Internal Server Error</h1>
    <p>Something went wrong on our end. Please try again later.</p>
    <a href='/public/index.php'>Go Home</a>
</body>
</html>";
        }
        exit;
    }
    
    /**
     * Show database connection error page
     */
    public static function showDatabaseError() {
        http_response_code(500);
        
        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }
        
        // Load database error view
        $viewFile = __DIR__ . '/../views/errors/database.php';
        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            // Fallback if view doesn't exist
            echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Connection Error</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        h1 { font-size: 36px; color: #f57c00; }
        p { color: #666; }
    </style>
</head>
<body>
    <h1>Database Connection Error</h1>
    <p>We're having trouble connecting to our database. Please try again later.</p>
    <a href='/public/index.php'>Go Home</a>
</body>
</html>";
        }
        exit;
    }
    
    /**
     * Get environment status
     */
    public static function isProduction() {
        return self::$isProduction;
    }
}
