<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../controllers/AdminAttractionsController.php';

$database = new Database();
$controller = new AdminAttractionsController($database->getConnection());

// Check if this is an edit request
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $controller->showEditForm();
} else {
    $controller->index();
}
