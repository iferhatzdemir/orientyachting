# Language System Integration Guide

This document provides instructions for integrating the multilingual system across the Orient Yacht Charter website.

## Database Setup

1. Run the SQL script in `data/translations.sql` to create the necessary tables:
   ```sql
   mysql -u root -p eticaret < data/translations.sql
   ```

## Files Overview

- **LanguageController.php** - Main class for language handling
- **TranslatableModel.php** - Model for translatable database content
- **autoload.php** - Class autoloader for PHP classes
- **lang_functions.php** - Helper functions for translations
- **static_translations.php** - Static translation strings by language
- **translations.sql** - Database schema for translations

## Integration Steps

### 1. Include Required Files

The language system is automatically initialized in `data/baglanti.php`. Make sure this file is included at the beginning of your application:

```php
require_once("data/baglanti.php");
```

### 2. Using Translation Functions

Use these helper functions in your templates and PHP files:

#### Basic Translation
```php
// Basic translation
echo t('site.welcome'); // Outputs: Welcome to our site

// With parameters
echo t('welcome.user', ['name' => 'John']); // Outputs: Welcome, John

// With context
echo tc('button', 'save'); // Outputs button-specific "Save" translation
```

#### Content Translation
```php
// Translate database content
$yacht_description = trans_content($yacht['description'], 'yachts_translations', 'description', $yacht['ID']);

// Translate multiple fields in a result set
$services = trans_array($services, 'services', ['title', 'description']);
```

#### Formatting Functions
```php
// Format date based on language
echo trans_date('2023-09-15'); // e.g., 15.09.2023 or 09/15/2023 depending on language

// Format currency based on language
echo trans_currency(1500, 'EUR'); // e.g., 1.500,00 € or €1,500.00
```

### 3. Implementing in Templates

#### Display Language Switcher
```php
// Simple language switcher
echo language_switcher();

// Custom language switcher
echo language_switcher([
    'container_class' => 'my-lang-switcher',
    'use_flags' => true
]);
```

#### JavaScript Translations
Add this to your header to make translations available in JavaScript:
```php
// In your head section
js_translations();

// Then in your JavaScript files or inline scripts:
const message = t('common.error');
```

### 4. Adding New Translations

#### Static Translations
Edit `data/static_translations.php` to add new static translations.

#### Database Translations
Use the admin interface to add translations, or use this code:

```php
global $Lang;
$Lang->addDatabaseTranslation('new.key', 'Translation value', 'en', 'context');
```

### 5. URL Structure

The language system supports URL-based language detection:

- Default language: `https://domain.com/page-name`
- Other languages: `https://domain.com/en/page-name`

Generate URLs with:
```php
global $Lang;
$url = $Lang->generateUrl('page-name');
```

## Best Practices

1. **Always use translation functions** instead of hardcoded strings
2. **Use context** for ambiguous terms that might have different translations
3. **Keep translation keys organized** by using prefixes like `common.`, `yacht.`, etc.
4. **Use translation fallbacks** for optional texts

## Technical Details

- Default language: Turkish (`tr`)
- Supported languages: Turkish, English, German, Russian, French
- PHP 7.3 compatible
- Translation keys are cached for performance 