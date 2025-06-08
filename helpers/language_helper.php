<?php
/**
 * Language Helper Functions
 * Provides multilingual support for both static and dynamic content
 */

/**
 * Global language arrays storage
 */
global $LANG_STRINGS;
$LANG_STRINGS = [];

/**
 * Get translation from static language files
 * 
 * @param string $key The translation key (e.g., 'nav.home')
 * @param array $params Optional parameters for placeholders
 * @return string The translated string or the key if not found
 */
function lang($key, $params = []) {
    global $LANG_STRINGS;
    
    // Get current language from session
    $currentLang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
    
    // Load language file if not already loaded
    if (empty($LANG_STRINGS[$currentLang])) {
        load_language_file($currentLang);
    }
    
    // Get translation or fallback to English
    if (isset($LANG_STRINGS[$currentLang][$key])) {
        $translation = $LANG_STRINGS[$currentLang][$key];
    } elseif ($currentLang != 'en') {
        // Try English as fallback
        if (empty($LANG_STRINGS['en'])) {
            load_language_file('en');
        }
        $translation = isset($LANG_STRINGS['en'][$key]) ? $LANG_STRINGS['en'][$key] : $key;
    } else {
        $translation = $key;
    }
    
    // Replace parameters if any
    if (!empty($params)) {
        foreach ($params as $param => $value) {
            $translation = str_replace(':' . $param, $value, $translation);
        }
    }
    
    return $translation;
}

/**
 * Load language file
 * 
 * @param string $lang Language code
 * @return bool Success status
 */
function load_language_file($lang) {
    global $LANG_STRINGS;
    
    $langFile = dirname(__DIR__) . '/lang/' . $lang . '.php';
    
    if (file_exists($langFile)) {
        $translations = include $langFile;
        if (is_array($translations)) {
            $LANG_STRINGS[$lang] = $translations;
            return true;
        }
    }
    
    // If language file doesn't exist or is invalid, log error
    error_log("Failed to load language file: {$langFile}");
    
    // Create empty array to prevent further load attempts
    $LANG_STRINGS[$lang] = [];
    
    return false;
}

/**
 * Get translation from database
 * 
 * @param string $key The translation key
 * @param array $params Optional parameters for placeholders
 * @return string The translated string or the key if not found
 */
function get_translation_from_db($key) {
    global $VT; // Using existing PDO-based database manager
    
    // Get current language from session
    $currentLang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
    
    // Try to get translation from database
    $translation = $VT->VeriGetir(
        "translations", 
        "WHERE key_name=? AND lang_code=?", 
        [$key, $currentLang], 
        "", 
        1
    );
    
    // If translation found, return it
    if ($translation && isset($translation['value'])) {
        return $translation['value'];
    }
    
    // Fallback to English if current language is not English
    if ($currentLang != 'en') {
        $fallback = $VT->VeriGetir(
            "translations", 
            "WHERE key_name=? AND lang_code=?", 
            [$key, 'en'], 
            "", 
            1
        );
        
        if ($fallback && isset($fallback['value'])) {
            return $fallback['value'];
        }
    }
    
    // Return key if no translation found
    return $key;
}

/**
 * Set or change current language
 * 
 * @param string $lang Language code
 */
function set_language($lang) {
    // Validate language code (only allow alphanumeric + underscore)
    if (!preg_match('/^[a-zA-Z0-9_]{2,5}$/', $lang)) {
        $lang = 'en'; // Default to English if invalid
    }
    
    $_SESSION['lang'] = $lang;
    
    // Also set 'dil' for backward compatibility
    $_SESSION['dil'] = $lang;
}

/**
 * Initialize language system
 */
function init_language_system() {
    // Initialize session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check for language change request
    if (isset($_GET['lang'])) {
        set_language($_GET['lang']);
    } elseif (!isset($_SESSION['lang'])) {
        // Set default language if not set
        set_language('en');
    }
    
    // Load language file for current language
    $currentLang = $_SESSION['lang'];
    load_language_file($currentLang);
}

// Initialize language system
init_language_system(); 