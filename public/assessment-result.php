<?php
/**
 * Assessment Result Page
 * Display assessment results
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/AssessmentController.php';

$controller = new AssessmentController();
$controller->result();
