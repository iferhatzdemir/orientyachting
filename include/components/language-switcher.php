<?php
/**
 * Language Switcher Component
 * 
 * A reusable component that displays a language selection dropdown
 * to be included in various parts of the site.
 * 
 * @param string $style The style of the switcher ('dropdown', 'buttons', or 'minimal')
 * @param bool $showFlags Whether to show flags next to language names
 */

// Default parameters
$style = isset($style) ? $style : 'dropdown';
$showFlags = isset($showFlags) ? $showFlags : true;

// Language configurations
$languages = [
    'en' => [
        'name' => 'English',
        'flag' => 'assets/img/flags/en.png'
    ],
    'tr' => [
        'name' => 'Türkçe',
        'flag' => 'assets/img/flags/tr.png'
    ],
    'de' => [
        'name' => 'Deutsch',
        'flag' => 'assets/img/flags/de.png'
    ],
    'ru' => [
        'name' => 'Русский',
        'flag' => 'assets/img/flags/ru.png'
    ]
];

// Get current page URL without language parameter
$currentUrl = $_SERVER['REQUEST_URI'];
$currentUrl = preg_replace('/[&?]lang=[a-z]{2}/', '', $currentUrl);
$separator = (strpos($currentUrl, '?') !== false) ? '&' : '?';

// Dropdown style
if ($style === 'dropdown'):
?>
<div class="lang-switcher">
    <div class="current-lang">
        <?php if ($showFlags && isset($languages[$lang]['flag'])): ?>
        <img src="<?php echo SITE . $languages[$lang]['flag']; ?>" alt="<?php echo $languages[$lang]['name']; ?>">
        <?php endif; ?>
        <span class="lang-code"><?php echo $lang; ?></span>
    </div>
    <div class="lang-dropdown">
        <?php foreach ($languages as $code => $language): ?>
            <a href="<?php echo $currentUrl . $separator . 'lang=' . $code; ?>" class="lang-item <?php echo ($lang === $code) ? 'active' : ''; ?>">
                <?php if ($showFlags && isset($language['flag'])): ?>
                <img src="<?php echo SITE . $language['flag']; ?>" alt="<?php echo $language['name']; ?>">
                <?php endif; ?>
                <span><?php echo $language['name']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>

<?php 
// Buttons style
elseif ($style === 'buttons'):
?>
<div class="lang-buttons">
    <?php foreach ($languages as $code => $language): ?>
        <a href="<?php echo $currentUrl . $separator . 'lang=' . $code; ?>" class="btn btn-sm <?php echo ($lang === $code) ? 'btn-primary' : 'btn-outline-primary'; ?> me-1">
            <?php if ($showFlags && isset($language['flag'])): ?>
            <img src="<?php echo SITE . $language['flag']; ?>" alt="<?php echo $language['name']; ?>" class="me-1" style="width: 16px; height: 12px;">
            <?php endif; ?>
            <span><?php echo $language['name']; ?></span>
        </a>
    <?php endforeach; ?>
</div>

<?php 
// Minimal style (just codes)
elseif ($style === 'minimal'):
?>
<div class="lang-minimal">
    <?php foreach ($languages as $code => $language): ?>
        <a href="<?php echo $currentUrl . $separator . 'lang=' . $code; ?>" class="lang-code <?php echo ($lang === $code) ? 'active' : ''; ?>">
            <?php echo strtoupper($code); ?>
        </a>
    <?php endforeach; ?>
</div>

<?php endif; ?> 