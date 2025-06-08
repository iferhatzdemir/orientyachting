<?php
/**
 * Language Controller Usage Example
 * 
 * This file demonstrates how to use the LanguageController class
 * for various translation operations.
 */

// Include database connection and base configuration
include_once "data/baglanti.php";

// Page title and meta data
$pageTitle = t('site.title') . ' - ' . t('language.example');
?>
<!DOCTYPE html>
<html lang="<?php echo $Lang->getHtmlLangAttribute(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo SITE; ?>css/bootstrap.min.css">
    <style>
        body { padding: 20px; }
        .example-section { margin-bottom: 30px; padding: 15px; border: 1px solid #ddd; border-radius: 5px; }
        .language-switcher { margin-bottom: 20px; }
        .language-switcher a { margin-right: 10px; padding: 5px 10px; text-decoration: none; }
        .language-switcher a.active { font-weight: bold; background-color: #f0f0f0; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 4px; }
    </style>
</head>
<body>

<div class="container">
    <h1><?php echo t('language.example_title'); ?></h1>
    <p><?php echo t('language.example_description'); ?></p>
    
    <!-- Language Switcher Automatic -->
    <h3><?php echo t('Automatic Language Switcher'); ?></h3>
    <?php echo $Lang->renderLanguageSwitcher([
        'use_flags' => true, 
        'display_names' => true,
        'container_class' => 'language-switcher mb-4'
    ]); ?>
    
    <!-- Language Switcher Manual -->
    <h3><?php echo t('Manual Language Switcher'); ?></h3>
    <div class="language-switcher mb-4">
        <?php foreach($Lang->getAvailableLanguages() as $code): ?>
            <a href="?lang=<?php echo $code; ?>" class="<?php echo $Lang->getCurrentLanguage() == $code ? 'active' : ''; ?>">
                <?php echo strtoupper($code); ?>
            </a>
        <?php endforeach; ?>
    </div>
    
    <!-- URL Generation Example -->
    <div class="example-section">
        <h3><?php echo t('URL Generation'); ?></h3>
        <p><?php echo t('URL generation examples with language codes:'); ?></p>
        
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo t('language.example_code'); ?></h4>
                <pre><code>// Generate URLs with language prefixes
$Lang->generateUrl('yatlar');
$Lang->generateUrl('yat/luxury-yacht-1');
$Lang->generateUrl('hizmetler/yat-yonetimi', [], 'en');
$Lang->generateUrl('iletisim', ['contact' => 'sales'], 'de');</code></pre>
            </div>
            <div class="col-md-6">
                <h4><?php echo t('language.example_result'); ?></h4>
                <p><strong>$Lang->generateUrl('yatlar'):</strong><br> 
                   <?php echo $Lang->generateUrl('yatlar'); ?></p>
                <p><strong>$Lang->generateUrl('yat/luxury-yacht-1'):</strong><br> 
                   <?php echo $Lang->generateUrl('yat/luxury-yacht-1'); ?></p>
                <p><strong>$Lang->generateUrl('hizmetler/yat-yonetimi', [], 'en'):</strong><br> 
                   <?php echo $Lang->generateUrl('hizmetler/yat-yonetimi', [], 'en'); ?></p>
                <p><strong>$Lang->generateUrl('iletisim', ['contact' => 'sales'], 'de'):</strong><br> 
                   <?php echo $Lang->generateUrl('iletisim', ['contact' => 'sales'], 'de'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Basic Translation Example -->
    <div class="example-section">
        <h3><?php echo t('language.basic_translation'); ?></h3>
        <p><?php echo t('language.basic_translation_desc'); ?></p>
        
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo t('language.example_code'); ?></h4>
                <pre><code>// Using t() function
echo t('yacht.title');

// Using with parameters
echo t('language.hello_name', ['name' => 'John']);

// Directly using the Lang object
echo $Lang->translate('yacht.features');</code></pre>
            </div>
            <div class="col-md-6">
                <h4><?php echo t('language.example_result'); ?></h4>
                <p><strong>t('yacht.title'):</strong> <?php echo t('yacht.title'); ?></p>
                <p><strong>t('language.hello_name', ['name' => 'John']):</strong> 
                   <?php echo t('language.hello_name', ['name' => 'John']); ?></p>
                <p><strong>$Lang->translate('yacht.features'):</strong> 
                   <?php echo $Lang->translate('yacht.features'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- Date & Currency Formatting -->
    <div class="example-section">
        <h3><?php echo t('language.formatting'); ?></h3>
        <p><?php echo t('language.formatting_desc'); ?></p>
        
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo t('language.example_code'); ?></h4>
                <pre><code>// Date formatting
echo translateDate('2023-04-15');
echo translateDate('2023-04-15', true);

// Currency formatting
echo translateCurrency(1500);
echo translateCurrency(1500, 'USD');</code></pre>
            </div>
            <div class="col-md-6">
                <h4><?php echo t('language.example_result'); ?></h4>
                <p><strong>translateDate('2023-04-15'):</strong> 
                   <?php echo translateDate('2023-04-15'); ?></p>
                <p><strong>translateDate('2023-04-15', true):</strong> 
                   <?php echo translateDate('2023-04-15', true); ?></p>
                <p><strong>translateCurrency(1500):</strong> 
                   <?php echo translateCurrency(1500); ?></p>
                <p><strong>translateCurrency(1500, 'USD'):</strong> 
                   <?php echo translateCurrency(1500, 'USD'); ?></p>
            </div>
        </div>
    </div>
    
    <!-- JavaScript Translations -->
    <div class="example-section">
        <h3><?php echo t('language.js_translation'); ?></h3>
        <p><?php echo t('language.js_translation_desc'); ?></p>
        
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo t('language.example_code'); ?></h4>
                <pre><code>// In PHP file
outputLanguageData();

// In JavaScript
console.log(t('yacht.title'));
console.log(window.currentLanguage);
console.log(t('language.hello_name', {name: 'John'}));</code></pre>
            </div>
            <div class="col-md-6">
                <h4><?php echo t('language.example_result'); ?></h4>
                <div id="js-example-result">
                    <p><?php echo t('language.loading'); ?>...</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- URL Structure Information -->
    <div class="example-section">
        <h3><?php echo t('URL Structure Information'); ?></h3>
        <p><?php echo t('The current URL structure information:'); ?></p>
        
        <div class="row">
            <div class="col-md-12">
                <pre><code><?php 
                    // Show parsed URL information
                    echo "Current URL: " . $_SERVER['REQUEST_URI'] . "\n";
                    echo "Current Language: " . $Lang->getCurrentLanguage() . "\n";
                    echo "Query Parameters: " . json_encode($_GET) . "\n";
                    
                    // Show automatically generated URLs for all languages
                    echo "\nLanguage URLs:\n";
                    foreach ($Lang->getLanguageSwitcherUrls() as $lang => $url) {
                        echo "$lang: $url\n";
                    }
                ?></code></pre>
            </div>
        </div>
    </div>
</div>

<?php outputLanguageData(); ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const resultDiv = document.getElementById('js-example-result');
    resultDiv.innerHTML = `
        <p><strong>t('yacht.title'):</strong> ${t('yacht.title')}</p>
        <p><strong>window.currentLanguage:</strong> ${window.currentLanguage}</p>
        <p><strong>t('language.hello_name', {name: 'John'}):</strong> ${t('language.hello_name', {name: 'John'})}</p>
    `;
});
</script>

</body>
</html> 