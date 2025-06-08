<?php
/**
 * Translation helper functions
 * 
 * These functions provide a simple interface for translating content
 * across the application.
 * 
 * @version 1.1
 */

// Global reference to the language controller
global $Lang;

if (!function_exists('t')) {
    /**
     * Translate a key
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in translation
     * @return string Translated text
     */
    function t($key, $params = []) {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'translate')) {
            return $Lang->translate($key, $params);
        }
        
        // Fallback: return the key itself or the default value if provided
        return isset($params['default']) ? $params['default'] : $key;
    }
}

if (!function_exists('tc')) {
    /**
     * Translate a key with context
     * 
     * @param string $key Translation key
     * @param string $context Context for the translation
     * @param array $params Parameters to replace in translation
     * @return string Translated text
     */
    function tc($key, $context = 'default', $params = []) {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'translateWithContext')) {
            return $Lang->translateWithContext($key, $context, $params);
        }
        
        // Fallback to regular translation
        return t($key, $params);
    }
}

if (!function_exists('e')) {
    /**
     * Translate and echo a key
     * 
     * @param string $key Translation key
     * @param array $params Parameters to replace in translation
     */
    function e($key, $params = []) {
        echo t($key, $params);
    }
}

if (!function_exists('ec')) {
    /**
     * Translate and echo a key with context
     * 
     * @param string $key Translation key
     * @param string $context Context for the translation
     * @param array $params Parameters to replace in translation
     */
    function ec($key, $context = 'default', $params = []) {
        echo tc($key, $context, $params);
    }
}

if (!function_exists('trans_content')) {
    /**
     * Translate database content
     * 
     * @param string $original Original content
     * @param string $table Table name
     * @param string $field Field name
     * @param int $contentID Content ID
     * @return string Translated content
     */
    function trans_content($original, $table, $field, $contentID) {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'translateContent')) {
            return $Lang->translateContent($original, $table, $field, $contentID);
        }
        
        return $original;
    }
}

if (!function_exists('trans_date')) {
    /**
     * Format date according to current language
     * 
     * @param string $date Date string in MySQL format (YYYY-MM-DD)
     * @param bool $withTime Include time in the output
     * @return string Formatted date
     */
    function trans_date($date, $withTime = false) {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'translateDate')) {
            return $Lang->translateDate($date, $withTime);
        }
        
        // Simple fallback
        if (empty($date)) return '';
        $format = $withTime ? 'd.m.Y H:i' : 'd.m.Y';
        return date($format, strtotime($date));
    }
}

if (!function_exists('trans_currency')) {
    /**
     * Format currency according to current language
     * 
     * @param float $amount Amount to format
     * @param string $currency Currency code
     * @return string Formatted amount
     */
    function trans_currency($amount, $currency = '') {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'translateCurrency')) {
            return $Lang->translateCurrency($amount, $currency);
        }
        
        // Simple fallback
        return number_format($amount, 2, ',', '.') . ' ₺';
    }
}

if (!function_exists('current_lang')) {
    /**
     * Get current language code
     * 
     * @return string Current language code
     */
    function current_lang() {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'getCurrentLanguage')) {
            return $Lang->getCurrentLanguage();
        }
        
        // Fallback to session or default
        return isset($_SESSION['lang']) ? $_SESSION['lang'] : 'tr';
    }
}

if (!function_exists('language_switcher')) {
    /**
     * Output language switcher HTML
     * 
     * @param array $options Switcher options
     * @return string HTML for language switcher
     */
    function language_switcher($options = []) {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'renderLanguageSwitcher')) {
            return $Lang->renderLanguageSwitcher($options);
        }
        
        // Simple fallback
        return '';
    }
}

if (!function_exists('js_translations')) {
    /**
     * Output JavaScript translations
     * 
     * @return void
     */
    function js_translations() {
        global $Lang;
        
        if (isset($Lang) && method_exists($Lang, 'outputJsTranslations')) {
            $Lang->outputJsTranslations();
        } else {
            // Simple fallback
            echo '<script>window.translations = {}; window.currentLanguage = "' . current_lang() . '";</script>';
        }
    }
} 