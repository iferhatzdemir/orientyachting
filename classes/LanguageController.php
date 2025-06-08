<?php
/**
 * Language Controller Class
 * 
 * This class handles all language-related operations including:
 * - Language detection
 * - Loading translations from database
 * - Loading static translations from file
 * - Translating content
 * 
 * @author Orient Yacht Charter
 * @version 1.0
 * @copyright 2023
 */

class LanguageController
{
    /**
     * PDO database connection
     * @var PDO
     */
    private $db;
    
    /**
     * Current language code (2 letters)
     * @var string
     */
    private $currentLang;
    
    /**
     * Available languages
     * @var array
     */
    private $availableLanguages = ['en', 'tr', 'de', 'ru'];
    
    /**
     * Default language
     * @var string
     */
    private $defaultLanguage = 'en';
    
    /**
     * VT class instance for database operations
     * @var VT
     */
    private $VT;
    
    /**
     * Current URL structure
     * @var array
     */
    private $currentUrlParts = [];
    
    /**
     * @var array Static translations loaded from file
     */
    private $staticTranslations;
    
    /**
     * @var array Database translations
     */
    private $dbTranslations;
    
    /**
     * @var array Translation cache to improve performance
     */
    private $translationCache = [];
    
    /**
     * Constructor
     * 
     * @param PDO $db Database connection
     * @param VT $VT VT class instance for database operations
     */
    public function __construct($db, $VT = null)
    {
        $this->db = $db;
        $this->VT = $VT;
        $this->parseCurrentUrl();
        $this->detectLanguage();
        
        // Load translations
        $this->loadStaticTranslations();
        $this->loadDatabaseTranslations();
    }
    
    /**
     * Parse current URL to extract language and page information
     * 
     * @return void
     */
    private function parseCurrentUrl()
    {
        // Get request URI
        $requestUri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        
        // Remove base path if present
        $baseUrl = '/orient/';
        if (strpos($requestUri, $baseUrl) === 0) {
            $requestUri = substr($requestUri, strlen($baseUrl));
        }
        
        // Remove query string
        $parts = explode('?', $requestUri);
        $path = $parts[0];
        
        // Extract path segments
        $segments = explode('/', trim($path, '/'));
        
        // Store URL parts for later use
        $this->currentUrlParts['segments'] = $segments;
        $this->currentUrlParts['original'] = $requestUri;
        $this->currentUrlParts['path'] = $path;
        
        // Check if first segment is a language code
        if (!empty($segments[0]) && in_array($segments[0], $this->availableLanguages)) {
            $this->currentUrlParts['lang'] = $segments[0];
            $this->currentUrlParts['page'] = isset($segments[1]) ? $segments[1] : '';
            $this->currentUrlParts['params'] = array_slice($segments, 2);
        } else {
            $this->currentUrlParts['lang'] = '';
            $this->currentUrlParts['page'] = isset($segments[0]) ? $segments[0] : '';
            $this->currentUrlParts['params'] = array_slice($segments, 1);
        }
    }
    
    /**
     * Detect current language from URL, session, or browser
     * 
     * @return void
     */
    public function detectLanguage()
    {
        // First check if language is in URL path
        if (!empty($this->currentUrlParts['lang'])) {
            $_SESSION['lang'] = $this->currentUrlParts['lang'];
        }
        // Then check if language is set in the URL params
        elseif (isset($_GET['lang']) && in_array($_GET['lang'], $this->availableLanguages)) {
            $_SESSION['lang'] = $_GET['lang'];
        } 
        // Check if language is set in the session
        elseif (!isset($_SESSION['lang']) || !in_array($_SESSION['lang'], $this->availableLanguages)) {
            // Default to browser language or default language
            $browserLang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '', 0, 2);
            $_SESSION['lang'] = in_array($browserLang, $this->availableLanguages) ? $browserLang : $this->defaultLanguage;
        }
        
        $this->currentLang = $_SESSION['lang'];
        
        // For backward compatibility
        $_SESSION["dil"] = $this->currentLang;
    }
    
    /**
     * Get current language
     * 
     * @return string Current language code
     */
    public function getCurrentLanguage()
    {
        return $this->currentLang;
    }
    
    /**
     * Get available languages
     * 
     * @return array List of available languages
     */
    public function getAvailableLanguages()
    {
        return $this->availableLanguages;
    }
    
    /**
     * Set available languages
     * 
     * @param array $languages List of language codes
     * @return void
     */
    public function setAvailableLanguages($languages)
    {
        if (is_array($languages) && !empty($languages)) {
            $this->availableLanguages = $languages;
        }
    }
    
    /**
     * Set default language
     * 
     * @param string $language Language code
     * @return void
     */
    public function setDefaultLanguage($language)
    {
        if (in_array($language, $this->availableLanguages)) {
            $this->defaultLanguage = $language;
        }
    }
    
    /**
     * Translate a key to the current language
     * 
     * @param string $key Translation key
     * @param array $params Optional parameters to replace in translation
     * @return string Translated text
     */
    public function translate($key, $params = [])
    {
        // Initialize return value as the key itself (fallback)
        $translation = $key;
        
        try {
            // Try to get translation from database
            $stmt = $this->db->prepare("SELECT translation_value FROM translations WHERE translation_key = ? AND lang_code = ? LIMIT 1");
            $stmt->execute([$key, $this->currentLang]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($result && !empty($result['translation_value'])) {
                $translation = $result['translation_value'];
            } else {
                // If not found in database, try static translations
                $static_translations = [];
                if (file_exists(__DIR__ . '/../data/static_translations.php')) {
                    $static_translations = include __DIR__ . '/../data/static_translations.php';
                }
                
                if (isset($static_translations[$this->currentLang]) && isset($static_translations[$this->currentLang][$key])) {
                    $translation = $static_translations[$this->currentLang][$key];
                } elseif (isset($params['default']) && !empty($params['default'])) {
                    $translation = $params['default'];
                }
            }
            
            // Replace any parameters in the translation
            if (!empty($params) && is_array($params)) {
                foreach ($params as $param_key => $param_value) {
                    if ($param_key !== 'default') {
                        $translation = str_replace('{' . $param_key . '}', $param_value, $translation);
                    }
                }
            }
            
        } catch (Exception $e) {
            // In case of any error, return the key itself
            error_log("Translation error: " . $e->getMessage());
            $translation = $key;
        }
        
        return $translation;
    }
    
    /**
     * Get all translations for the current language
     * 
     * @return array All translations for the current language
     */
    public function getAllTranslations()
    {
        $translations = [];
        
        try {
            // Get all translations from database for the current language
            $stmt = $this->db->prepare("SELECT translation_key, translation_value FROM translations WHERE lang_code = ?");
            $stmt->execute([$this->currentLang]);
            $db_translations = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
            
            if ($db_translations) {
                $translations = $db_translations;
            }
            
            // Get static translations
            $static_translations = [];
            if (file_exists(__DIR__ . '/../data/static_translations.php')) {
                $static_translations = include __DIR__ . '/../data/static_translations.php';
            }
            
            if (isset($static_translations[$this->currentLang])) {
                // Merge database translations with static translations
                // Database translations take precedence
                $translations = array_merge($static_translations[$this->currentLang], $translations);
            }
            
        } catch (Exception $e) {
            // In case of any error, return empty array
            error_log("Error getting translations: " . $e->getMessage());
            $translations = [];
        }
        
        return $translations;
    }
    
    /**
     * Translate content from the database
     * 
     * @param string $original Original text
     * @param string $table Translation table name
     * @param string $field Field name
     * @param int $contentID Content ID
     * @return string Translated text
     */
    public function translateContent($original, $table, $field, $contentID)
    {
        // Return original text for default language
        if ($this->currentLang === 'tr') {
            return $original;
        }
        
        // Only proceed if VT class is available
        if ($this->VT === null) {
            return $original;
        }
        
        // Get translation from database
        $translation = $this->VT->VeriGetir(
            $table, 
            "WHERE content_id=? AND lang=? AND field=?", 
            array($contentID, $this->currentLang, $field), 
            "ORDER BY ID ASC", 
            1
        );
        
        // Return translation if found
        if ($translation !== false && !empty($translation[0]["translation"])) {
            return $translation[0]["translation"];
        }
        
        // Return original text if no translation found
        return $original;
    }
    
    /**
     * Translate an array of content
     * 
     * @param array $content Content array
     * @param string $table Table name
     * @param array $fields Fields to translate
     * @return array Translated content array
     */
    public function translateArray($content, $table, $fields)
    {
        // Return null if content is empty
        if (!$content) {
            return null;
        }
        
        // Return content if not an array
        if (!is_array($content)) {
            return $content;
        }
        
        // If content is a result set (multiple rows)
        if (isset($content[0]) && is_array($content[0])) {
            foreach ($content as $key => $item) {
                foreach ($fields as $field) {
                    if (isset($item[$field]) && isset($item["ID"])) {
                        $content[$key][$field] = $this->translateContent($item[$field], $table . "_translations", $field, $item["ID"]);
                    }
                }
            }
        } else {
            // If it's a single row
            foreach ($fields as $field) {
                if (isset($content[$field]) && isset($content["ID"])) {
                    $content[$field] = $this->translateContent($content[$field], $table . "_translations", $field, $content["ID"]);
                }
            }
        }
        
        return $content;
    }
    
    /**
     * Format date according to current language
     * 
     * @param string $date MySQL date format (YYYY-MM-DD)
     * @param bool $withTime Include time information
     * @return string Formatted date
     */
    public function translateDate($date, $withTime = false)
    {
        if (empty($date)) {
            return "";
        }
        
        $format = 'd.m.Y';
        if ($withTime) {
            $format .= ' H:i';
        }
        
        // Set date format according to language
        switch ($this->currentLang) {
            case "en":
                $format = 'Y-m-d';
                if ($withTime) {
                    $format .= ' H:i';
                }
                break;
            case "de":
            case "ru":
                $format = 'd.m.Y';
                if ($withTime) {
                    $format .= ' H:i';
                }
                break;
        }
        
        // Format date
        $dateObj = new DateTime($date);
        return $dateObj->format($format);
    }
    
    /**
     * Format currency according to current language
     * 
     * @param float $amount Amount
     * @param string $currency Currency code
     * @return string Formatted currency
     */
    public function translateCurrency($amount, $currency = '')
    {
        // Default currency
        if (empty($currency)) {
            $currency = "TRY";
        }
        
        // Format according to language
        switch ($this->currentLang) {
            case 'en':
                $symbol = ($currency == "TRY") ? "₺" : "$";
                return $symbol . ' ' . number_format($amount, 0, '.', ',');
            case 'de':
                $symbol = ($currency == "TRY") ? "₺" : "€";
                return $symbol . ' ' . number_format($amount, 0, ',', '.');
            case 'ru':
                $symbol = ($currency == "TRY") ? "₺" : "₽";
                return $symbol . ' ' . number_format($amount, 0, ',', ' ');
            default: // Türkçe
                return number_format($amount, 0, ',', '.') . ' ₺';
        }
    }
    
    /**
     * Output JavaScript translation object and function
     * 
     * @return void
     */
    public function outputJsTranslations()
    {
        $translations = $this->getAllTranslations();
        
        echo '<script>';
        echo 'window.translations = ' . json_encode($translations) . ';';
        echo 'window.currentLanguage = "' . $this->currentLang . '";';
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
    
    /**
     * Get HTML lang attribute value
     * 
     * @return string Language code for HTML lang attribute
     */
    public function getHtmlLangAttribute()
    {
        return $this->currentLang;
    }
    
    /**
     * Get language suffix for field names
     * 
     * @return string Language suffix (e.g. "_en", "_de", empty for Turkish)
     */
    public function getLanguageSuffix()
    {
        return $this->currentLang != "tr" ? "_" . $this->currentLang : "";
    }
    
    /**
     * Generate language-specific URL
     * 
     * @param string $page Page name
     * @param array $params URL parameters
     * @param string $lang Language code (default: current language)
     * @return string Language-specific URL
     */
    public function generateUrl($page, $params = [], $lang = '')
    {
        global $siteurl;
        
        // Use current language if none specified
        if (empty($lang)) {
            $lang = $this->currentLang;
        }
        
        // Generate URL parts
        $url = $siteurl;
        
        // Only add language code if not default language or force language in URL is enabled
        if ($lang != '' && $lang != $this->defaultLanguage) {
            $url .= $lang . '/';
        }
        
        // Add page name if provided
        if (!empty($page)) {
            $url .= $page;
        }
        
        // Add parameters if provided
        if (!empty($params)) {
            $url .= '?' . http_build_query($params);
        }
        
        return $url;
    }
    
    /**
     * Generate language switcher URLs
     * 
     * @param bool $preservePage Whether to preserve the current page in the URL
     * @return array Language codes mapped to URLs
     */
    public function getLanguageSwitcherUrls($preservePage = true)
    {
        $urls = [];
        
        // Get current page and parameters
        $page = isset($_GET['sayfa']) ? $_GET['sayfa'] : '';
        $params = $_GET;
        unset($params['sayfa']);
        unset($params['lang']);
        
        // Generate URL for each language
        foreach ($this->availableLanguages as $langCode) {
            if ($preservePage && !empty($page)) {
                $urls[$langCode] = $this->generateUrl($page, $params, $langCode);
            } else {
                $urls[$langCode] = $this->generateUrl('', $params, $langCode);
            }
        }
        
        return $urls;
    }
    
    /**
     * Generate language switcher HTML
     * 
     * @param array $options Options for rendering (class, activeClass, etc.)
     * @return string HTML for language switcher
     */
    public function renderLanguageSwitcher($options = [])
    {
        // Default options
        $defaultOptions = [
            'container_class' => 'language-switcher',
            'item_class' => 'lang-item',
            'active_class' => 'active',
            'use_flags' => false,
            'flag_path' => 'assets/img/flags/',
            'display_names' => true
        ];
        
        // Merge with provided options
        $options = array_merge($defaultOptions, $options);
        
        // Get language URLs
        $urls = $this->getLanguageSwitcherUrls();
        
        // Language display names
        $langNames = [
            'tr' => 'Türkçe',
            'en' => 'English',
            'de' => 'Deutsch',
            'ru' => 'Русский'
        ];
        
        // Start output
        $html = '<div class="' . $options['container_class'] . '">';
        
        // Add each language
        foreach ($this->availableLanguages as $code) {
            $activeClass = ($code == $this->currentLang) ? ' ' . $options['active_class'] : '';
            $html .= '<a href="' . $urls[$code] . '" class="' . $options['item_class'] . $activeClass . '">';
            
            // Add flag if enabled
            if ($options['use_flags']) {
                $html .= '<img src="' . $options['flag_path'] . $code . '.png" alt="' . $code . ' flag" />';
            }
            
            // Add language name or code
            if ($options['display_names']) {
                $html .= isset($langNames[$code]) ? $langNames[$code] : strtoupper($code);
            } else {
                $html .= strtoupper($code);
            }
            
            $html .= '</a>';
        }
        
        $html .= '</div>';
        
        return $html;
    }
    
    /**
     * Load static translations from file
     */
    private function loadStaticTranslations()
    {
        $translationsFile = dirname(__DIR__) . '/data/static_translations.php';
        
        if (file_exists($translationsFile)) {
            $this->staticTranslations = include $translationsFile;
        } else {
            $this->staticTranslations = [];
            error_log("Static translations file not found: $translationsFile");
        }
    }
    
    /**
     * Load translations from database
     */
    private function loadDatabaseTranslations()
    {
        $this->dbTranslations = [];
        
        try {
            $query = "SELECT t.translation_key, t.context, t.translation_value 
                     FROM translations t 
                     JOIN languages l ON t.lang_code = l.lang_code 
                     WHERE t.lang_code = ? AND l.active = 1";
            
            $stmt = $this->db->prepare($query);
            $stmt->execute([$this->currentLang]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $context = $row['context'] ?: 'default';
                $key = $row['translation_key'];
                $this->dbTranslations[$context][$key] = $row['translation_value'];
            }
        } catch (Exception $e) {
            error_log("Error loading database translations: " . $e->getMessage());
        }
    }
    
    /**
     * Translate a key with context support
     * 
     * @param string $key Translation key to look up
     * @param string $context Context for the translation (default, navigation, etc.)
     * @param array $params Parameters to substitute in the translation
     * @return string Translated text or original key if not found
     */
    public function translateWithContext($key, $context = 'default', $params = [])
    {
        // Check if translation is already cached
        $cacheKey = $this->currentLang . '.' . $context . '.' . $key;
        if (isset($this->translationCache[$cacheKey])) {
            $translation = $this->translationCache[$cacheKey];
        } else {
            // Try to get from database first (takes precedence)
            if (isset($this->dbTranslations[$context][$key])) {
                $translation = $this->dbTranslations[$context][$key];
            } 
            // Then try static translations
            elseif (isset($this->staticTranslations[$this->currentLang][$context][$key])) {
                $translation = $this->staticTranslations[$this->currentLang][$context][$key];
            } 
            // Try default context as fallback
            elseif ($context !== 'default' && isset($this->staticTranslations[$this->currentLang]['default'][$key])) {
                $translation = $this->staticTranslations[$this->currentLang]['default'][$key];
            }
            // Try english as fallback
            elseif ($this->currentLang !== 'en' && isset($this->staticTranslations['en'][$context][$key])) {
                $translation = $this->staticTranslations['en'][$context][$key];
            }
            // Fallback to the key itself
            else {
                $translation = $key;
            }
            
            // Cache the result
            $this->translationCache[$cacheKey] = $translation;
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
     * Add a new translation to the database
     * 
     * @param string $key Translation key
     * @param string $value Translation value
     * @param string $langCode Language code
     * @param string $context Context for the translation
     * @return bool Success status
     */
    public function addDatabaseTranslation($key, $value, $langCode, $context = 'default')
    {
        try {
            // Check if translation already exists
            $query = "SELECT id FROM translations 
                     WHERE translation_key = ? AND lang_code = ? AND context = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$key, $langCode, $context]);
            
            if ($stmt->rowCount() > 0) {
                // Update existing translation
                $query = "UPDATE translations 
                         SET translation_value = ? 
                         WHERE translation_key = ? AND lang_code = ? AND context = ?";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$value, $key, $langCode, $context]);
            } else {
                // Insert new translation
                $query = "INSERT INTO translations 
                         (translation_key, translation_value, lang_code, context) 
                         VALUES (?, ?, ?, ?)";
                $stmt = $this->db->prepare($query);
                $result = $stmt->execute([$key, $value, $langCode, $context]);
            }
            
            // Clear cache for this key
            $cacheKey = $langCode . '.' . $context . '.' . $key;
            unset($this->translationCache[$cacheKey]);
            
            // If we're updating the current language, update the in-memory translations
            if ($langCode === $this->currentLang) {
                $this->dbTranslations[$context][$key] = $value;
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error adding database translation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a translation from the database
     * 
     * @param string $key Translation key
     * @param string $langCode Language code
     * @param string $context Context for the translation
     * @return bool Success status
     */
    public function deleteDatabaseTranslation($key, $langCode, $context = 'default')
    {
        try {
            $query = "DELETE FROM translations 
                     WHERE translation_key = ? AND lang_code = ? AND context = ?";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([$key, $langCode, $context]);
            
            // Clear cache for this key
            $cacheKey = $langCode . '.' . $context . '.' . $key;
            unset($this->translationCache[$cacheKey]);
            
            // If we're updating the current language, update the in-memory translations
            if ($langCode === $this->currentLang && isset($this->dbTranslations[$context][$key])) {
                unset($this->dbTranslations[$context][$key]);
            }
            
            return true;
        } catch (Exception $e) {
            error_log("Error deleting database translation: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all translations for a specific language
     * 
     * @param string $langCode Language code
     * @return array All translations for the language
     */
    public function getAllTranslationsForLanguage($langCode)
    {
        $translations = [];
        
        try {
            $query = "SELECT translation_key, context, translation_value 
                     FROM translations 
                     WHERE lang_code = ?";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$langCode]);
            
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $context = $row['context'] ?: 'default';
                $key = $row['translation_key'];
                $translations[$context][$key] = $row['translation_value'];
            }
            
            // Add static translations
            if (isset($this->staticTranslations[$langCode])) {
                foreach ($this->staticTranslations[$langCode] as $context => $contextTranslations) {
                    if (!isset($translations[$context])) {
                        $translations[$context] = [];
                    }
                    
                    foreach ($contextTranslations as $key => $value) {
                        // Only add if not already in database translations
                        if (!isset($translations[$context][$key])) {
                            $translations[$context][$key] = $value;
                        }
                    }
                }
            }
        } catch (Exception $e) {
            error_log("Error getting all translations: " . $e->getMessage());
        }
        
        return $translations;
    }
} 