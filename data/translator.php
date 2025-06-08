<?php
/**
 * Translation Utility Functions
 * 
 * This file contains utility functions to handle translations from
 * both database and static files.
 * 
 * Note: These functions are now made conditional to avoid conflicts with
 * the LanguageController-based functions in baglanti.php.
 */

if (!function_exists('t')) {
    /**
     * Get translation for a specific key in the current language
     * 
     * First tries to get the translation from the database.
     * If not found, falls back to static translations.
     * If still not found, returns the key itself as fallback.
     * 
     * @param string $key The translation key
     * @param array $params Optional parameters to replace in the translation
     * @return string The translated text
     */
    function t($key, $params = []) {
        global $db, $lang;
        
        // Initialize return value as the key itself (fallback)
        $translation = $key;
        
        try {
            // Try to get translation from database
            $stmt = $db->prepare("SELECT translation_value FROM translations WHERE translation_key = ? AND lang_code = ? LIMIT 1");
            $stmt->execute([$key, $lang]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['translation_value'])) {
                $translation = $result['translation_value'];
            } else {
                // If not found in database, try static translations
                $static_translations = include __DIR__ . '/static_translations.php';
                
                if (isset($static_translations[$lang]) && isset($static_translations[$lang][$key])) {
                    $translation = $static_translations[$lang][$key];
                }
            }
            
            // Replace any parameters in the translation
            if (!empty($params) && is_array($params)) {
                foreach ($params as $param_key => $param_value) {
                    $translation = str_replace('{' . $param_key . '}', $param_value, $translation);
                }
            }
            
        } catch (Exception $e) {
            // In case of any error, return the key itself
            $translation = $key;
        }
        
        return $translation;
    }
}

if (!function_exists('getAllTranslations')) {
    /**
     * Get all translations for the current language
     * 
     * This function is useful for JavaScript localization
     * 
     * @return array All translations for the current language
     */
    function getAllTranslations() {
        global $db, $lang;
        
        $translations = [];
        
        try {
            // Get all translations from database for the current language
            $stmt = $db->prepare("SELECT translation_key, translation_value FROM translations WHERE lang_code = ?");
            $stmt->execute([$lang]);
            $db_translations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            if ($db_translations) {
                $translations = $db_translations;
            }
            
            // Get static translations
            $static_translations = include __DIR__ . '/static_translations.php';
            
            if (isset($static_translations[$lang])) {
                // Merge database translations with static translations
                // Database translations take precedence
                $translations = array_merge($static_translations[$lang], $translations);
            }
            
        } catch (Exception $e) {
            // In case of any error, return empty array
            $translations = [];
        }
        
        return $translations;
    }
}

if (!function_exists('outputJsTranslations')) {
    /**
     * Output all translations as a JavaScript object
     * 
     * This function is useful for using translations in JavaScript
     */
    function outputJsTranslations() {
        $translations = getAllTranslations();
        
        echo '<script>';
        echo 'window.translations = ' . json_encode($translations) . ';';
        echo 'function t(key, params) {';
        echo '  let translation = window.translations[key] || key;';
        echo '  if (params) {';
        echo '    Object.keys(params).forEach(function(paramKey) {';
        echo '      translation = translation.replace("{" + paramKey + "}", params[paramKey]);';
        echo '    });';
        echo '  }';
        echo '  return translation;';
        echo '}';
        echo '</script>';
    }
}
?> 