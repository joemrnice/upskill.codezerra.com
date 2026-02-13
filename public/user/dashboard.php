<?php
/**
 * User Dashboard
 */

require_once __DIR__ . '/../../app/bootstrap.php';

try {
    Router::executeController('DashboardController', 'index');
} catch (Exception $e) {
    error_log("Dashboard page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

