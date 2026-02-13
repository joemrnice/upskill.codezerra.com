<?php
/**
 * Login Process Handler
 */

require_once __DIR__ . '/../../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        Router::executeController('AuthController', 'login');
    } catch (Exception $e) {
        error_log("Login process error: " . $e->getMessage());
        Session::setFlash('error', 'An error occurred during login. Please try again.');
        redirect(base_url('public/auth/login.php'));
    }
} else {
    redirect(base_url('public/auth/login.php'));
}

