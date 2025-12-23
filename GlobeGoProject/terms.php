<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/TermsController.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

$controller = new TermsController($db);
$controller->index();

