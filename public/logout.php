<?php
/**
 * LEYECO III Utility Report System
 * Logout Handler
 */

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/functions.php';
require_once __DIR__ . '/../app/AuthController.php';

$authController = new AuthController();
$authController->logout();

setFlashMessage('success', 'You have been logged out successfully');
redirect('homepage.php');
