<?php
// Site configuration
define('SITE_NAME', 'GlobeGo');
define('SITE_URL', 'http://localhost/GlobeGoProject');
define('ADMIN_EMAIL', 'admin@globego.com');
define('SUPPORT_EMAIL', 'support@globego.com');
define('SUPPORT_PHONE', '+1 (555) 123-4567');

// Session configuration - skip during PHPUnit tests
if (!defined('PHPUNIT_RUNNING') && session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('UTC');

// Include database
require_once 'database.php';

// Include Language helper
require_once __DIR__ . '/../helpers/Language.php';

// Initialize language system
Language::init();

// Helper functions
function redirect($url) {
    header("Location: " . $url);
    exit();
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserRole() {
    return $_SESSION['user_role'] ?? null;
}

function isAdmin() {
    return getUserRole() === 'admin';
}

function isGuide() {
    return getUserRole() === 'guide';
}

function isTourist() {
    return getUserRole() === 'tourist';
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function generateToken() {
    return bin2hex(random_bytes(32));
}

function formatPrice($price) {
    return '$' . number_format($price, 2);
}

function formatDate($date) {
    return date('M d, Y', strtotime($date));
}

function formatDateTime($datetime) {
    return date('M d, Y H:i', strtotime($datetime));
}

/**
 * Translation helper function
 * @param string $key Translation key (e.g., 'nav.home')
 * @param array $params Parameters to replace in translation
 * @return string Translated text
 */
function t($key, $params = []) {
    return Language::translate($key, $params);
}
?>
