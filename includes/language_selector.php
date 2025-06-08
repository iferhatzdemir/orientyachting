<?php
/**
 * Language Selector Component
 * Displays language options in the header
 */

/**
 * Available languages
 */
$available_languages = [
    'en' => [
        'name' => 'English',
        'flag' => 'assets/img/flags/gb.png'
    ],
    'tr' => [
        'name' => 'Türkçe',
        'flag' => 'assets/img/flags/tr.png'
    ],
    'de' => [
        'name' => 'Deutsch',
        'flag' => 'assets/img/flags/de.png'
    ]
];

// Get current language
$current_lang = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';

// Generate current URL without lang parameter
$current_url = strtok($_SERVER['REQUEST_URI'], '?');
$query_params = $_GET;
unset($query_params['lang']);
$query_string = http_build_query($query_params);
if (!empty($query_string)) {
    $current_url .= '?' . $query_string;
}
?>

<div class="language-selector">
    <div class="dropdown">
        <button class="btn dropdown-toggle" type="button" id="languageDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <?php if (isset($available_languages[$current_lang])): ?>
                <img src="<?= SITE . $available_languages[$current_lang]['flag'] ?>" alt="<?= $available_languages[$current_lang]['name'] ?>" width="24" height="16">
                <span class="d-none d-md-inline"><?= $available_languages[$current_lang]['name'] ?></span>
            <?php else: ?>
                <img src="<?= SITE ?>assets/img/flags/gb.png" alt="English" width="24" height="16">
                <span class="d-none d-md-inline">English</span>
            <?php endif; ?>
        </button>
        <div class="dropdown-menu" aria-labelledby="languageDropdown">
            <?php foreach ($available_languages as $code => $language): ?>
                <a class="dropdown-item <?= ($current_lang == $code) ? 'active' : '' ?>" 
                   href="<?= $current_url . (strpos($current_url, '?') !== false ? '&' : '?') . 'lang=' . $code ?>">
                    <img src="<?= SITE . $language['flag'] ?>" alt="<?= $language['name'] ?>" width="24" height="16">
                    <?= $language['name'] ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div> 