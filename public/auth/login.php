<?php
/**
 * Login Page
 */

require_once __DIR__ . '/../../app/bootstrap.php';

try {
    Router::executeController('AuthController', 'showLogin');
} catch (Exception $e) {
    error_log("Login page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

