<?php
// Admin users MVC entry point
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AdminUsersController.php';

$database = new Database();
$controller = new AdminUsersController($database->getConnection());
$controller->index();
