<?php
/**
 * Assessment Submission Handler
 * Process assessment/quiz submission
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/AssessmentController.php';

header('Content-Type: application/json');

$controller = new AssessmentController();
$controller->submit();
