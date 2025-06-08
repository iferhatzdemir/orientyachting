<?php
/**
 * Helpers Functions
 * 
 * General helper functions for the Orient Yacht Charter website
 * Compatible with PHP 7.3
 */

/**
 * Get translation for a specific key in the specified language
 * 
 * First tries to get the translation from the database.
 * If not found, falls back to static translations.
 * 
 * @param string $key The translation key
 * @param string $lang The language code
 * @return string The translated text or empty string if not found
 */
function getTranslation(string $key, string $lang): string {
    global $db; // Use the global database connection
    
    // Try to get translation from database first
    try {
        $stmt = $db->prepare("SELECT translation_value as value FROM translations WHERE translation_key = ? AND lang_code = ?");
        $stmt->execute([$key, $lang]);
        $record = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($record) {
            return $record['value'];
        }
    } catch (Exception $e) {
        // Optionally log error
        // error_log("Translation error: " . $e->getMessage());
    }
    
    // Fallback to static translations
    $static = include 'static_translations.php';
    
    // Return the static translation if it exists, otherwise empty string
    if (isset($static[$lang]) && isset($static[$lang][$key])) {
        return $static[$lang][$key];
    }
    
    return '';
}

/**
 * Get translation with current language from session
 * 
 * A shorthand function for getTranslation that uses the current language
 * 
 * @param string $key The translation key
 * @return string The translated text or empty string if not found
 */
function t(string $key): string {
    // Get current language from session
    $lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
    
    return getTranslation($key, $lang);
}

/**
 * Format translated text with parameters
 * 
 * Allows placeholders like {name} to be replaced with values
 * 
 * @param string $text The text with placeholders
 * @param array $params The parameters to replace in the text
 * @return string The formatted text
 */
function formatTranslation(string $text, array $params = []): string {
    if (empty($params)) {
        return $text;
    }
    
    foreach ($params as $key => $value) {
        $text = str_replace('{'.$key.'}', $value, $text);
    }
    
    return $text;
}

/**
 * Get and format translation in one step
 * 
 * @param string $key The translation key
 * @param array $params The parameters to replace in the translation
 * @return string The translated and formatted text
 */
function tf(string $key, array $params = []): string {
    $text = t($key);
    return formatTranslation($text, $params);
} 