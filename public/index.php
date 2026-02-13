<?php
/**
 * Front Controller
 * Main entry point for all requests
 * Shows landing page for guests, dashboard for logged-in users
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    // Check if user is logged in
    if (Session::isLoggedIn()) {
        // Show dashboard for logged-in users
        Router::executeController('DashboardController', 'index');
    } else {
        // Show landing page for guests
        Router::executeController('HomeController', 'index');
    }
} catch (Exception $e) {
    error_log("Index page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

