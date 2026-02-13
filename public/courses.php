<?php
/**
 * Course Catalog Page
 * Shows all published courses with search/filter/sort
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    Router::executeController('CourseController', 'index');
} catch (Exception $e) {
    error_log("Courses page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

