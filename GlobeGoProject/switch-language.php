<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/controllers/LanguageController.php';

$controller = new LanguageController();
$controller->switchLanguage();

