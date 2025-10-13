# Magento 2 RTL CSS Extension

This repository provides a Magento 2 module for automatic Right-to-Left (RTL) CSS processing, enabling support for RTL languages and layouts in Magento 2 storefronts.

## Features

- **Automatic RTL CSS Generation:** Uses the [rtlcss](https://rtlcss.com/) tool to transform standard CSS files into RTL equivalents for supported languages.  
- **Locale Detection:** Automatically detects the store view's locale and applies RTL direction and CSS processing for languages such as Arabic, Hebrew, and Persian.  
- **HTML Direction Attribute:** Dynamically sets the `dir="rtl"` attribute on the HTML element for RTL locales.  
- **Hyvä Theme Support:** Includes plugins to adjust SVG icons for RTL layouts in Hyvä-based themes.  
- **Selective CSS Processing:** Only processes relevant CSS files for RTL conversion, such as `styles-m.css`, `styles-l.css`, and email templates.

## Supported RTL Languages

- Arabic (Algeria, Egypt, Kuwait, Morocco, Saudi Arabia)  
- Persian (Iran)  
- Hebrew (Israel)

## How It Works

- The module hooks into Magento’s asset pre-processing pipeline.  
- When a store view uses an RTL locale, the extension uses `npx rtlcss` to convert CSS content to RTL format.  
- Ensures the required `rtlcss` Node.js package is installed in the module directory.  
- Modifies the HTML output to set the correct `dir` attribute for RTL locales.  
- Plugins for Hyvä adjust icon directions (e.g., left/right arrows) to match RTL expectations.

## Installation

Install the module via Composer:

```bash
composer require studioraz/magento2-rtlcss
```
Then enable and update the module:
```bash
php bin/magento module:enable SR_RTLCss
php bin/magento setup:upgrade
```
Ensure that Node.js is available on your system, as it is required for the rtlcss package.

Usage
	•	The module works automatically for supported locales.
	•	No manual configuration is required; CSS conversion and HTML direction adjustment are handled transparently.
