<?php
/**
 * Registration Page
 */

require_once __DIR__ . '/../../app/bootstrap.php';

try {
    Router::executeController('AuthController', 'showRegister');
} catch (Exception $e) {
    error_log("Register page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

