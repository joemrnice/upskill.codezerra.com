<?php
/**
 * Course Details Page
 * Shows individual course details with modules and enrollment button
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

$controller = new CourseController();
$controller->show();
