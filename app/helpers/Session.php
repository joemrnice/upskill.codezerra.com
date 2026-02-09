<?php
/**
 * Session Helper Class
 * Handles session management and security
 */

class Session {
    private static $started = false;
    
    /**
     * Start session with secure configuration
     */
    public static function start() {
        if (self::$started) {
            return;
        }
        
        // Secure session configuration
        ini_set('session.cookie_httponly', 1);
        ini_set('session.use_only_cookies', 1);
        ini_set('session.cookie_secure', isset($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off');
        ini_set('session.cookie_samesite', 'Strict');
        
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
            self::$started = true;
            
            // Regenerate session ID if not set
            if (!isset($_SESSION['initiated'])) {
                session_regenerate_id(true);
                $_SESSION['initiated'] = true;
            }
        }
    }
    
    /**
     * Set session variable
     */
    public static function set($key, $value) {
        self::start();
        $_SESSION[$key] = $value;
    }
    
    /**
     * Get session variable
     */
    public static function get($key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }
    
    /**
     * Check if session variable exists
     */
    public static function has($key) {
        self::start();
        return isset($_SESSION[$key]);
    }
    
    /**
     * Remove session variable
     */
    public static function remove($key) {
        self::start();
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
        }
    }
    
    /**
     * Destroy session
     */
    public static function destroy() {
        self::start();
        $_SESSION = [];
        
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        session_destroy();
        self::$started = false;
    }
    
    /**
     * Regenerate session ID
     */
    public static function regenerate() {
        self::start();
        session_regenerate_id(true);
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return self::has('user_id');
    }
    
    /**
     * Check if user is admin
     */
    public static function isAdmin() {
        return self::get('user_role') === 'admin';
    }
    
    /**
     * Get current user ID
     */
    public static function getUserId() {
        return self::get('user_id');
    }
    
    /**
     * Get current user data
     */
    public static function getUser() {
        return [
            'id' => self::get('user_id'),
            'name' => self::get('user_name'),
            'email' => self::get('user_email'),
            'role' => self::get('user_role'),
        ];
    }
    
    /**
     * Set flash message
     */
    public static function setFlash($type, $message) {
        self::set('flash', [
            'type' => $type,
            'message' => $message
        ]);
    }
    
    /**
     * Get and clear flash message
     */
    public static function getFlash() {
        $flash = self::get('flash');
        self::remove('flash');
        return $flash;
    }
    
    /**
     * Generate CSRF token
     */
    public static function generateToken() {
        $token = bin2hex(random_bytes(32));
        self::set('csrf_token', $token);
        return $token;
    }
    
    /**
     * Verify CSRF token
     */
    public static function verifyToken($token) {
        return self::has('csrf_token') && hash_equals(self::get('csrf_token'), $token);
    }
}
