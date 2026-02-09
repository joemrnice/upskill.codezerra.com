<?php
/**
 * Process Enrollment
 * Handles POST request to enroll user in a course
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

$controller = new CourseController();
$controller->processEnroll();
