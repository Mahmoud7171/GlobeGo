<?php
/**
 * PHPUnit Bootstrap File
 * This file is executed before running tests
 */

// Define that we're running tests (to prevent session_start in config.php)
define('PHPUNIT_RUNNING', true);

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Include autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}

// Set error reporting for tests
error_reporting(E_ALL);
ini_set('display_errors', 1);

