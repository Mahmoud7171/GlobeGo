<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/FinesController.php';

$database = new Database();
$controller = new FinesController($database->getConnection());
$controller->payFine();


