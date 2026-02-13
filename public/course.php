<?php
/**
 * Course Details Page
 * Shows individual course details with modules and enrollment button
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    Router::executeController('CourseController', 'show');
} catch (Exception $e) {
    error_log("Course page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

