<?php
/**
 * Mark Resource as Complete (AJAX Endpoint)
 * Handles POST request to mark a resource as completed
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/controllers/LearningController.php';

$controller = new LearningController();
$controller->markComplete();
