<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../helpers/Language.php';

class LanguageController {
    /**
     * Switch language
     */
    public function switchLanguage() {
        if (isset($_GET['lang'])) {
            $lang = $_GET['lang'];
            if (Language::setLanguage($lang)) {
                // Redirect back to the previous page or home
                $redirect = $_SERVER['HTTP_REFERER'] ?? SITE_URL . '/index.php';
                header("Location: " . $redirect);
                exit();
            }
        }
        
        // If language switch failed, redirect to home
        redirect(SITE_URL . '/index.php');
    }
}

