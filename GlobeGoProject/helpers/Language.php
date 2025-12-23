<?php
/**
 * Language Helper Class
 * Manages translations and language switching
 */
class Language {
    private static $currentLang = 'en';
    private static $translations = [];
    private static $supportedLanguages = ['en', 'ar', 'fr', 'es'];
    private static $rtlLanguages = ['ar'];
    
    /**
     * Initialize language system
     */
    public static function init() {
        // Get language from session or default to English
        if (isset($_SESSION['language']) && in_array($_SESSION['language'], self::$supportedLanguages)) {
            self::$currentLang = $_SESSION['language'];
        } else {
            // Try to detect from browser
            self::$currentLang = self::detectBrowserLanguage();
            $_SESSION['language'] = self::$currentLang;
        }
        
        // Load translations
        self::loadTranslations();
    }
    
    /**
     * Detect browser language
     */
    private static function detectBrowserLanguage() {
        if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
            $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
            foreach ($langs as $lang) {
                $lang = strtolower(substr(trim($lang), 0, 2));
                if (in_array($lang, self::$supportedLanguages)) {
                    return $lang;
                }
            }
        }
        return 'en'; // Default to English
    }
    
    /**
     * Load translation file
     */
    private static function loadTranslations() {
        $langFile = __DIR__ . '/../lang/' . self::$currentLang . '.php';
        if (file_exists($langFile)) {
            self::$translations = require $langFile;
        } else {
            // Fallback to English if translation file doesn't exist
            $enFile = __DIR__ . '/../lang/en.php';
            if (file_exists($enFile)) {
                self::$translations = require $enFile;
            }
        }
    }
    
    /**
     * Get translation for a key
     * @param string $key Translation key
     * @param array $params Parameters to replace in translation
     * @return string Translated text
     */
    public static function translate($key, $params = []) {
        $keys = explode('.', $key);
        $value = self::$translations;
        
        foreach ($keys as $k) {
            if (isset($value[$k])) {
                $value = $value[$k];
            } else {
                // Return key if translation not found
                return $key;
            }
        }
        
        // Replace parameters if provided
        if (!empty($params) && is_string($value)) {
            foreach ($params as $paramKey => $paramValue) {
                $value = str_replace(':' . $paramKey, $paramValue, $value);
            }
        }
        
        return $value;
    }
    
    /**
     * Alias for translate() - shorter function name
     */
    public static function t($key, $params = []) {
        return self::translate($key, $params);
    }
    
    /**
     * Get current language code
     */
    public static function getCurrentLang() {
        return self::$currentLang;
    }
    
    /**
     * Set language
     */
    public static function setLanguage($lang) {
        if (in_array($lang, self::$supportedLanguages)) {
            self::$currentLang = $lang;
            $_SESSION['language'] = $lang;
            self::loadTranslations();
            return true;
        }
        return false;
    }
    
    /**
     * Get supported languages
     */
    public static function getSupportedLanguages() {
        return self::$supportedLanguages;
    }
    
    /**
     * Check if current language is RTL
     */
    public static function isRTL() {
        return in_array(self::$currentLang, self::$rtlLanguages);
    }
    
    /**
     * Get language name
     */
    public static function getLanguageName($code = null) {
        $code = $code ?? self::$currentLang;
        $names = [
            'en' => 'English',
            'ar' => 'العربية',
            'fr' => 'Français',
            'es' => 'Español'
        ];
        return $names[$code] ?? $code;
    }
    
    /**
     * Get language flag emoji
     */
    public static function getLanguageFlag($code = null) {
        $code = $code ?? self::$currentLang;
        $flags = [
            'en' => '🇬🇧', // British flag for English
            'ar' => '🇪🇬', // Egypt flag for Arabic
            'fr' => '🇫🇷', // France flag for French
            'es' => '🇪🇸'  // Spain flag for Spanish
        ];
        return $flags[$code] ?? '🌐';
    }
    
    /**
     * Get all languages with flags
     */
    public static function getAllLanguages() {
        return [
            'en' => '🇬🇧',
            'ar' => '🇪🇬',
            'fr' => '🇫🇷',
            'es' => '🇪🇸'
        ];
    }
    
    /**
     * Get all languages with names (for tooltips/accessibility)
     */
    public static function getAllLanguagesWithNames() {
        return [
            'en' => ['flag' => '🇬🇧', 'name' => 'English'],
            'ar' => ['flag' => '🇪🇬', 'name' => 'العربية'],
            'fr' => ['flag' => '🇫🇷', 'name' => 'Français'],
            'es' => ['flag' => '🇪🇸', 'name' => 'Español']
        ];
    }
}

