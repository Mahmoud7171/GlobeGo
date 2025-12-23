<?php
// Admin guides management entry point
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AdminGuidesController.php';

$database = new Database();
$controller = new AdminGuidesController($database->getConnection());
$controller->index();



