<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/ReportsController.php';

$database = new Database();
$controller = new ReportsController($database->getConnection());
$controller->index();











