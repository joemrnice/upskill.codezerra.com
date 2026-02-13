<?php
/**
 * Authentication Controller
 * Handles user authentication, registration, and password reset
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../helpers/Session.php';
require_once __DIR__ . '/../helpers/Validator.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        try {
            $this->userModel = new User();
        } catch (Exception $e) {
            error_log("AuthController initialization error: " . $e->getMessage());
            // If database connection fails, it will be caught by ErrorHandler
            throw $e;
        }
    }
    
    /**
     * Show login page
     */
    public function showLogin() {
        // Redirect if already logged in
        if (Session::isLoggedIn()) {
            redirect(base_url('public/index.php'));
        }
        
        view('auth/login');
    }
    
    /**
     * Process login
     */
    public function login() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/auth/login.php'));
        }
        
        // Sanitize input
        $email = Validator::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validate input
        $validator = new Validator([
            'email' => $email,
            'password' => $password
        ]);
        
        $validator->required('email', 'Email is required.')
                  ->email('email', 'Please enter a valid email address.')
                  ->required('password', 'Password is required.');
        
        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            redirect(base_url('public/auth/login.php'));
        }
        
        try {
            // Verify credentials
            $user = $this->userModel->verifyCredentials($email, $password);
            
            if (!$user) {
                Session::setFlash('error', 'Invalid email or password.');
                redirect(base_url('public/auth/login.php'));
            }
            
            // Check if user is active
            if ($user['status'] !== 'active') {
                Session::setFlash('error', 'Your account has been suspended. Please contact administrator.');
                redirect(base_url('public/auth/login.php'));
            }
            
            // Regenerate session ID for security
            Session::regenerate();
            
            // Set session variables
            Session::set('user_id', $user['id']);
            Session::set('user_name', $user['name']);
            Session::set('user_email', $user['email']);
            Session::set('user_role', $user['role']);
            
            // Handle remember me
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', true, true);
                // In production, store token hash in database
            }
            
            Session::setFlash('success', 'Welcome back, ' . $user['name'] . '!');
            
            // Redirect based on role
            if ($user['role'] === 'admin') {
                redirect(base_url('public/admin/dashboard.php'));
            } else {
                redirect(base_url('public/index.php'));
            }
        } catch (Exception $e) {
            error_log("Login error: " . $e->getMessage());
            Session::setFlash('error', 'An error occurred during login. Please try again later.');
            redirect(base_url('public/auth/login.php'));
        }
    }
    
    /**
     * Show registration page
     */
    public function showRegister() {
        // Redirect if already logged in
        if (Session::isLoggedIn()) {
            redirect(base_url('public/index.php'));
        }
        
        view('auth/register');
    }
    
    /**
     * Process registration
     */
    public function register() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/auth/register.php'));
        }
        
        // Sanitize input
        $data = [
            'name' => Validator::sanitize($_POST['name'] ?? ''),
            'email' => Validator::sanitize($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'employee_id' => Validator::sanitize($_POST['employee_id'] ?? '')
        ];
        
        // Validate input
        $validator = new Validator($data);
        
        $validator->required('name', 'Name is required.')
                  ->min('name', 3, 'Name must be at least 3 characters.')
                  ->max('name', 100, 'Name must not exceed 100 characters.')
                  ->required('email', 'Email is required.')
                  ->email('email', 'Please enter a valid email address.')
                  ->unique('email', 'users', 'email', null, 'Email already registered.')
                  ->required('employee_id', 'Employee ID is required.')
                  ->unique('employee_id', 'users', 'employee_id', null, 'Employee ID already registered.')
                  ->required('password', 'Password is required.')
                  ->min('password', 8, 'Password must be at least 8 characters.')
                  ->required('confirm_password', 'Please confirm your password.')
                  ->matches('confirm_password', 'password', 'Passwords do not match.');
        
        if ($validator->fails()) {
            $errors = $validator->getErrors();
            Session::set('errors', $errors);
            Session::set('old_input', $data);
            redirect(base_url('public/auth/register.php'));
        }
        
        // Create user
        try {
            $userId = $this->userModel->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'employee_id' => $data['employee_id'],
                'role' => 'user',
                'status' => 'active'
            ]);
            
            if ($userId) {
                Session::setFlash('success', 'Registration successful! Please login to continue.');
                redirect(base_url('public/auth/login.php'));
            } else {
                Session::setFlash('error', 'Registration failed. Please try again.');
                Session::set('old_input', $data);
                redirect(base_url('public/auth/register.php'));
            }
        } catch (Exception $e) {
            Session::setFlash('error', 'An error occurred during registration. Please try again.');
            Session::set('old_input', $data);
            redirect(base_url('public/auth/register.php'));
        }
    }
    
    /**
     * Logout user
     */
    public function logout() {
        // Verify CSRF token for POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
                Session::setFlash('error', 'Invalid request.');
                redirect(base_url('public/index.php'));
            }
        }
        
        // Set flash message before destroying session
        Session::setFlash('success', 'You have been logged out successfully.');
        
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', true, true);
        }
        
        Session::destroy();
        redirect(base_url('public/auth/login.php'));
    }
    
    /**
     * Show forgot password page
     */
    public function showForgotPassword() {
        // Redirect if already logged in
        if (Session::isLoggedIn()) {
            redirect(base_url('public/index.php'));
        }
        
        view('auth/forgot-password');
    }
    
    /**
     * Process forgot password request
     */
    public function forgotPassword() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        // Sanitize input
        $email = Validator::sanitize($_POST['email'] ?? '');
        
        // Validate input
        $validator = new Validator(['email' => $email]);
        $validator->required('email', 'Email is required.')
                  ->email('email', 'Please enter a valid email address.');
        
        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        // Find user
        $user = $this->userModel->findByEmail($email);
        
        // Always show success message (don't reveal if email exists)
        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Save token to database
            $this->userModel->setResetToken($email, $token, $expires);
            
            // In production, send email with reset link
            $resetLink = base_url('public/auth/reset-password.php?token=' . $token);
            
            // For development, log the reset link
            error_log("Password reset link for {$email}: {$resetLink}");
            
            // Simulate email sending
            Session::setFlash('success', 'Password reset instructions have been sent to your email address.');
        } else {
            // Don't reveal that email doesn't exist
            Session::setFlash('success', 'Password reset instructions have been sent to your email address.');
        }
        
        redirect(base_url('public/auth/forgot-password.php'));
    }
    
    /**
     * Show reset password page
     */
    public function showResetPassword() {
        // Redirect if already logged in
        if (Session::isLoggedIn()) {
            redirect(base_url('public/index.php'));
        }
        
        // Get token from URL
        $token = $_GET['token'] ?? '';
        
        if (empty($token)) {
            Session::setFlash('error', 'Invalid reset token.');
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        // Verify token exists and is not expired
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user) {
            Session::setFlash('error', 'Invalid or expired reset token. Please request a new one.');
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        view('auth/reset-password', ['token' => $token]);
    }
    
    /**
     * Process password reset
     */
    public function resetPassword() {
        // Verify CSRF token
        if (!isset($_POST['csrf_token']) || !Session::verifyToken($_POST['csrf_token'])) {
            Session::setFlash('error', 'Invalid request. Please try again.');
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        // Get token and passwords
        $token = Validator::sanitize($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate input
        $validator = new Validator([
            'password' => $password,
            'confirm_password' => $confirmPassword
        ]);
        
        $validator->required('password', 'Password is required.')
                  ->min('password', 8, 'Password must be at least 8 characters.')
                  ->required('confirm_password', 'Please confirm your password.')
                  ->matches('confirm_password', 'password', 'Passwords do not match.');
        
        if ($validator->fails()) {
            Session::setFlash('error', $validator->getFirstError());
            redirect(base_url('public/auth/reset-password.php?token=' . $token));
        }
        
        // Verify token
        $user = $this->userModel->findByResetToken($token);
        
        if (!$user) {
            Session::setFlash('error', 'Invalid or expired reset token. Please request a new one.');
            redirect(base_url('public/auth/forgot-password.php'));
        }
        
        // Update password
        $success = $this->userModel->updatePassword($user['id'], $password);
        
        if ($success) {
            // Clear reset token
            $this->userModel->clearResetToken($user['id']);
            
            Session::setFlash('success', 'Your password has been reset successfully. Please login with your new password.');
            redirect(base_url('public/auth/login.php'));
        } else {
            Session::setFlash('error', 'Failed to reset password. Please try again.');
            redirect(base_url('public/auth/reset-password.php?token=' . $token));
        }
    }
}
