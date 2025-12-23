<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/helpers/Language.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['text']) || !isset($input['lang'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$text = $input['text'];
$lang = $input['lang'];

// Validate language
if (!in_array($lang, ['en', 'ar', 'fr', 'es'])) {
    $lang = 'en';
}

// Set language temporarily for translation
$originalLang = Language::getCurrentLang();
Language::setLanguage($lang);

// Load chatbot translations
function loadChatbotTranslations($lang) {
    $translationFile = __DIR__ . '/lang/chatbot_' . $lang . '.php';
    if (file_exists($translationFile)) {
        return require $translationFile;
    }
    // Fallback to English
    return require __DIR__ . '/lang/chatbot_en.php';
}

// Simple translation function for common phrases
function translateChatbotText($text, $lang) {
    // Load translations for the language
    $translations = loadChatbotTranslations($lang);
    
    // Check for specific known responses
    // Prices response - check multiple patterns
    if (strpos($text, 'Tour prices on GlobeGo vary') !== false || 
        strpos($text, 'PRICE RANGE:') !== false ||
        strpos($text, 'PRICE BREAKDOWN') !== false ||
        strpos($text, 'Lowest: $55') !== false ||
        strpos($text, 'Highest: $400') !== false ||
        strpos($text, 'BUDGET ($55-$70)') !== false ||
        strpos($text, 'London Tower Bridge Experience - $55') !== false) {
        return $translations['prices_general'] ?? $text;
    }
    
    // Out of scope message
    if (strpos($text, "I can't answer this question") !== false) {
        return $translations['out_of_scope'] ?? $text;
    }
    
    // Default greeting
    if (strpos($text, "I'm Globoba, your GlobeGo assistant!") !== false ||
        strpos($text, 'DESTINATIONS:') !== false && strpos($text, 'GUIDES:') !== false) {
        return $translations['default_greeting'] ?? $text;
    }
    
    // For other responses, try pattern-based translation
    $patterns = [
        'ar' => [
            '/Tour prices on GlobeGo/' => 'أسعار الجولات في GlobeGo',
            '/PRICE RANGE:/' => 'نطاق الأسعار:',
            '/BUDGET/' => 'الميزانية',
            '/MID-RANGE/' => 'المتوسطة',
            '/PREMIUM/' => 'المميزة',
            '/LUXURY/' => 'الفاخرة',
            '/CHEAPEST/' => 'الأرخص',
            '/MOST EXPENSIVE/' => 'الأغلى',
            '/Price:/' => 'السعر:',
            '/Duration:/' => 'المدة:',
            '/Category:/' => 'الفئة:',
            '/Find Tours by Price:/' => 'العثور على الجولات حسب السعر:',
        ],
        'fr' => [
            '/Tour prices on GlobeGo/' => 'Les prix des visites sur GlobeGo',
            '/PRICE RANGE:/' => 'GAMME DE PRIX:',
            '/BUDGET/' => 'BUDGET',
            '/MID-RANGE/' => 'MOYENNE GAMME',
            '/PREMIUM/' => 'PREMIUM',
            '/LUXURY/' => 'LUXE',
            '/CHEAPEST/' => 'LE MOINS CHER',
            '/MOST EXPENSIVE/' => 'LE PLUS CHER',
            '/Price:/' => 'Prix:',
            '/Duration:/' => 'Durée:',
            '/Category:/' => 'Catégorie:',
            '/Find Tours by Price:/' => 'Trouver des visites par prix:',
        ],
        'es' => [
            '/Tour prices on GlobeGo/' => 'Los precios de los tours en GlobeGo',
            '/PRICE RANGE:/' => 'RANGO DE PRECIOS:',
            '/BUDGET/' => 'PRESUPUESTO',
            '/MID-RANGE/' => 'RANGO MEDIO',
            '/PREMIUM/' => 'PREMIUM',
            '/LUXURY/' => 'LUJO',
            '/CHEAPEST/' => 'MÁS BARATO',
            '/MOST EXPENSIVE/' => 'MÁS CARO',
            '/Price:/' => 'Precio:',
            '/Duration:/' => 'Duración:',
            '/Category:/' => 'Categoría:',
            '/Find Tours by Price:/' => 'Encontrar Tours por Precio:',
        ]
    ];
    
    $translated = $text;
    if (isset($patterns[$lang])) {
        foreach ($patterns[$lang] as $pattern => $replacement) {
            $translated = preg_replace($pattern, $replacement, $translated);
        }
    }
    
    return $translated;
}

$translated = translateChatbotText($text, $lang);
echo json_encode(['translated' => $translated]);

// Restore original language
Language::setLanguage($originalLang);

