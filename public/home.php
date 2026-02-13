<?php
/**
 * Landing Page
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    Router::executeController('HomeController', 'index');
} catch (Exception $e) {
    error_log("Home page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

