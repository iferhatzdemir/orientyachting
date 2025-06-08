<?php
/**
 * Language Selector Component
 * Provides fallback functionality for language selection
 */

if (!function_exists('renderLanguageSelector')) {
    function renderLanguageSelector($currentLang = 'en') {
        $languages = [
            'en' => 'English',
            'tr' => 'Türkçe',
            'de' => 'Deutsch',
            'ru' => 'Русский'
        ];
        
        // Start building the language selector HTML
        $html = '<div class="lang-switcher">';
        $html .= '<div class="current-lang">';
        $html .= '<img src="' . SITE . 'assets/img/flags/' . $currentLang . '-flag.png" alt="' . $currentLang . '">';
        $html .= '<span class="lang-code">' . $currentLang . '</span>';
        $html .= '<i class="fas fa-chevron-down ml-2"></i>';
        $html .= '</div>';
        
        $html .= '<div class="lang-dropdown">';
        foreach ($languages as $code => $name) {
            $activeClass = ($currentLang == $code) ? 'active' : '';
            $html .= '<a href="?lang=' . $code . '" class="lang-item ' . $activeClass . '">';
            $html .= '<img src="' . SITE . 'assets/img/flags/' . $code . '-flag.png" alt="' . $name . '">';
            $html .= '<span>' . $name . '</span>';
            $html .= '</a>';
        }
        $html .= '</div>';
        $html .= '</div>';
        
        return $html;
    }
}

if (!function_exists('outputLanguageData')) {
    function outputLanguageData() {
        // Fallback implementation - just output the minimal JS required
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Language selector functionality
            const langSwitcher = document.querySelector(".lang-switcher");
            const langDropdown = document.querySelector(".lang-dropdown");
            
            if (langSwitcher && langDropdown) {
                langSwitcher.addEventListener("click", function(e) {
                    langDropdown.style.display = langDropdown.style.display === "block" ? "none" : "block";
                    langDropdown.style.opacity = langDropdown.style.opacity === "1" ? "0" : "1";
                    langDropdown.style.transform = langDropdown.style.transform === "translateY(0px)" ? "translateY(10px)" : "translateY(0px)";
                    e.stopPropagation();
                });
                
                document.addEventListener("click", function() {
                    langDropdown.style.display = "none";
                    langDropdown.style.opacity = "0";
                    langDropdown.style.transform = "translateY(10px)";
                });
            }
        });
        </script>';
    }
}
?> 