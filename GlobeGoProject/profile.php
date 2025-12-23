<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/ProfileController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new ProfileController($db);
$controller->index();
