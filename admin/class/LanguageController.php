<?php
/**
 * LanguageController Class
 * Handles language-related functionality
 */
class LanguageController {
    private $db;
    private $currentLang;
    private $translations = [];
    
    /**
     * Constructor
     * 
     * @param object $db Database connection
     * @param string $lang Current language code
     */
    public function __construct($db = null, $lang = 'en') {
        $this->db = $db;
        $this->currentLang = $lang;
        $this->loadTranslations();
    }
    
    /**
     * Load translations from language files
     */
    private function loadTranslations() {
        $langFile = __DIR__ . '/../../lang/' . $this->currentLang . '.php';
        
        if (file_exists($langFile)) {
            include $langFile;
            
            if (isset($lang) && is_array($lang)) {
                $this->translations = $lang;
            }
        }
    }
    
    /**
     * Get translation for a key
     * 
     * @param string $key Translation key
     * @param array $params Parameters for translation
     * @return string Translated text
     */
    public function translate($key, $params = []) {
        // Check if key exists in translations
        if (isset($this->translations[$key])) {
            return $this->translations[$key];
        }
        
        // If key not found, return fallback or key itself
        return isset($params['fallback']) ? $params['fallback'] : $key;
    }
    
    /**
     * Get current language
     * 
     * @return string Current language code
     */
    public function getCurrentLang() {
        return $this->currentLang;
    }
    
    /**
     * Set current language
     * 
     * @param string $lang Language code
     */
    public function setCurrentLang($lang) {
        $this->currentLang = $lang;
        $this->loadTranslations();
    }
}

// Create a global translate function if it doesn't exist
if (!function_exists('t')) {
    /**
     * Global translation function
     * 
     * @param string $key Translation key
     * @param array $params Parameters for translation
     * @return string Translated text
     */
    function t($key, $params = []) {
        global $languageController;
        
        if (isset($languageController) && $languageController instanceof LanguageController) {
            return $languageController->translate($key, $params);
        }
        
        // If language controller not available, return fallback or key
        return isset($params['fallback']) ? $params['fallback'] : $key;
    }
}
?> 