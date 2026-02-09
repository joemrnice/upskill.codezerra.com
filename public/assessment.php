<?php
/**
 * Assessment Page
 * Display assessment/quiz taking interface
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/AssessmentController.php';

$controller = new AssessmentController();
$controller->show();
