<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/HomeController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new HomeController($db);
$controller->index();
