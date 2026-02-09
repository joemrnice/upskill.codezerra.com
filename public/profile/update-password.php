<?php
/**
 * Update Password Handler
 */

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/controllers/ProfileController.php';

$controller = new ProfileController();
$controller->updatePassword();
