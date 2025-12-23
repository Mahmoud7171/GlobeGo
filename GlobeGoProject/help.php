<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/HelpController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new HelpController($db);
$controller->index();

