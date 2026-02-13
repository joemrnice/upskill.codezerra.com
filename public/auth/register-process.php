<?php
/**
 * Registration Process Handler
 */

require_once __DIR__ . '/../../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        Router::executeController('AuthController', 'register');
    } catch (Exception $e) {
        error_log("Registration process error: " . $e->getMessage());
        Session::setFlash('error', 'An error occurred during registration. Please try again.');
        redirect(base_url('public/auth/register.php'));
    }
} else {
    redirect(base_url('public/auth/register.php'));
}

