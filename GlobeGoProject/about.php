<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/AboutController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new AboutController($db);
$controller->index();

