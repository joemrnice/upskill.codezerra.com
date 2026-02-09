<?php
/**
 * Front Controller
 * Main entry point for all requests
 * Shows landing page for guests, dashboard for logged-in users
 */

require_once __DIR__ . '/../app/bootstrap.php';

// Check if user is logged in
if (Session::isLoggedIn()) {
    // Show dashboard for logged-in users
    require_once __DIR__ . '/../app/controllers/DashboardController.php';
    $dashboardController = new DashboardController();
    $dashboardController->index();
} else {
    // Show landing page for guests
    require_once __DIR__ . '/../app/controllers/HomeController.php';
    $homeController = new HomeController();
    $homeController->index();
}
