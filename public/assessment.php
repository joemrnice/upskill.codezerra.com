<?php
/**
 * Assessment Page
 * Display assessment/quiz taking interface
 */

require_once __DIR__ . '/../app/bootstrap.php';

try {
    Router::executeController('AssessmentController', 'show');
} catch (Exception $e) {
    error_log("Assessment page error: " . $e->getMessage());
    ErrorHandler::show500Error();
}

