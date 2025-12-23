# Multi-Language Support

GlobeGo now supports multiple languages: English, Arabic, French, and Spanish.

## How It Works

The language system automatically detects the user's browser language preference and sets it accordingly. Users can also manually switch languages using the language switcher in the navigation bar.

## Language Files

Translation files are located in the `lang/` directory:
- `en.php` - English (default)
- `ar.php` - Arabic (العربية) - RTL support
- `fr.php` - French (Français)
- `es.php` - Spanish (Español)

## Using Translations in Views

### Basic Usage

```php
<?php echo Language::t('nav.home'); ?>
```

Or use the shorthand helper function:

```php
<?php echo t('nav.home'); ?>
```

### With Parameters

```php
<?php echo Language::t('welcome.message', ['name' => $userName]); ?>
```

In the translation file:
```php
'welcome' => [
    'message' => 'Welcome, :name!'
]
```

## Translation Keys Structure

Translations are organized by sections:
- `nav.*` - Navigation items
- `common.*` - Common UI elements
- `home.*` - Home page content
- `auth.*` - Authentication pages
- `footer.*` - Footer content
- `chatbot.*` - Chatbot messages
- `validation.*` - Form validation messages

## RTL Support

Arabic automatically enables RTL (Right-to-Left) layout. The system:
- Sets `dir="rtl"` on the HTML element
- Loads Bootstrap RTL CSS
- Adjusts margins and padding for RTL layout
- Reverses navigation order

## Adding New Translations

1. Add the English translation to `lang/en.php`
2. Add translations for all other languages in their respective files
3. Use the translation key in your views: `<?php echo t('your.key'); ?>`

## Language Switcher

The language switcher is automatically included in the header navigation. Users can click the globe icon to select their preferred language.

## Session Storage

The selected language is stored in the user's session, so it persists across page loads.

