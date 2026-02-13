<?php
/**
 * Learning Interface Page
 * Shows course content with module navigation and video player
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    Router::executeController('LearningController', 'show');
} catch (Exception $e) {
    error_log("Learning page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

