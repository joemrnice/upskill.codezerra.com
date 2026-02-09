<?php
/**
 * Learning Interface Page
 * Shows course content with module navigation and video player
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/LearningController.php';

$controller = new LearningController();
$controller->show();
