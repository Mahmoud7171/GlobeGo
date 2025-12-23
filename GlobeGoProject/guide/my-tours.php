<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/GuideTourController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new GuideTourController($db);
$controller->myTours();
