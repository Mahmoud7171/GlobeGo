<?php
// Admin bookings MVC entry point
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AdminBookingsController.php';

$database = new Database();
$controller = new AdminBookingsController($database->getConnection());
$controller->index();
