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

## Releases

Releases follow the [shared Release Please guide](https://github.com/studioraz/ci-templates/blob/master/docs/release-please.md).
Use Conventional Commit PR titles (`feat: add support`, `fix: correct behavior`).
Squash merges use the PR title and body; single-commit PRs must have a matching commit title.

Pushes to `master`, the Monday 06:00 UTC schedule and manual runs maintain the release PR and synchronize shared configuration.
Merging a release PR creates a GitHub Release and an unprefixed SemVer tag, and updates the root `CHANGELOG.md`.
Enrollment preserves existing release history (verified baseline `1.7.0`).

The optional `RELEASE_TOKEN` is forwarded to the shared workflow. Without it, `GITHUB_TOKEN` is used and bot-created PRs do not trigger PR-title CI. Supply a PAT or GitHub App token when those checks are required.
