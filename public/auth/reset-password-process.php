<?php
/**
 * Reset Password Process Handler
 */

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $authController = new AuthController();
    $authController->resetPassword();
} else {
    redirect(base_url('public/auth/reset-password.php'));
}
