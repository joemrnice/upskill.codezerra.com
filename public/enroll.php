<?php
/**
 * Enrollment Confirmation Page
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

$controller = new CourseController();
$controller->enroll();
