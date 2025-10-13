# Magento 2 RTL CSS Extension

This repository provides a Magento 2 module for automatic Right-to-Left (RTL) CSS processing, enabling support for RTL languages and layouts in Magento 2 storefronts.

## Features

- **Automatic RTL CSS Generation:** Uses the [rtlcss](https://rtlcss.com/) tool to transform standard CSS files into RTL equivalents for supported languages.
- **Locale Detection:** Automatically detects the store view's locale and applies RTL direction and CSS processing for languages such as Arabic, Hebrew, and Persian.
- **HTML Direction Attribute:** Dynamically sets the `dir="rtl"` attribute on the HTML element for RTL locales.
- **Hyva Theme Support:** Includes plugins to adjust SVG icons for RTL layouts in Hyva-based themes.
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
- Plugins for Hyva adjust icon directions (e.g., left/right arrows) to match RTL expectations.

## Installation

1. Clone the repository into your Magento 2 installation’s `app/code` directory.
2. Run `composer install` and ensure Node.js is available.
3. The module will automatically install the `rtlcss` package if not present.
4. Enable the module:
   ```bash
   php bin/magento module:enable SR_RTLCss
   php bin/magento setup:upgrade
   ```

## Usage

- The module works automatically for supported locales.
- No manual configuration is required; CSS conversion and HTML direction adjustment are handled transparently.

## License

See [LICENSE](LICENSE) for details.

## Maintainer

[Studio Raz](https://github.com/studioraz)

---

For more details, see the source code in the [`src`](./src/) directory.
