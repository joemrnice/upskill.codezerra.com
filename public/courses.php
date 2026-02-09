<?php
/**
 * Course Catalog Page
 * Shows all published courses with search/filter/sort
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/CourseController.php';

$controller = new CourseController();
$controller->index();
