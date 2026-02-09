<?php
/**
 * Profile Index Page
 */

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/controllers/ProfileController.php';

$controller = new ProfileController();
$controller->index();
