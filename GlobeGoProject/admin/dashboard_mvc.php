<?php
// Admin dashboard MVC entry point
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AdminDashboardController.php';

$database = new Database();
$controller = new AdminDashboardController($database->getConnection());
$controller->index();
