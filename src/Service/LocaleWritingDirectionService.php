<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace SR\RTLCss\Service;

use Magento\Framework\Locale\Resolver;

class LocaleWritingDirectionService
{
    public const HTML_ATTRIBUTE_DIR = 'dir';
    public const HTML_ATTRIBUTE_DIR_VALUE_RTL = 'rtl';
    public const HTML_ATTRIBUTE_DIR_VALUE_LTR = 'ltr';



    /**
     * @see \Magento\Framework\Locale\Config::$_allowedLocales
     */
    public const RTL_LANGUAGES = [
        'ar_DZ', // Arabic (Algeria)
        'ar_EG', // Arabic (Egypt)
        'ar_KW', // Arabic (Kuwait)
        'ar_MA', // Arabic (Morocco)
        'ar_SA', // Arabic (Saudi Arabia)
        'fa_IR', // Persian (Iran)
        'he_IL', // Hebrew (Israel)
    ];

    public const CSS_FILE_PATHS = [
        'css/styles-m.css',
        'css/styles-m.min.css',
        'css/styles-l.css',
        'css/styles-l.min.css',
        'css/email.css',
        'css/email-inline.css'
    ];

    public function __construct(
        protected Resolver $localeResolver
    ) {
    }

    public function getStoreViewContentDirection(): string
    {
        $storeLang = $this->localeResolver->getLocale();

        return $this->isRtlLanguage($storeLang) ? self::HTML_ATTRIBUTE_DIR_VALUE_RTL : self::HTML_ATTRIBUTE_DIR_VALUE_LTR;
    }

    public function isStoreViewContentDirectionRtl(): bool
    {
        $storeLang = $this->localeResolver->getLocale();

        return $this->isRtlLanguage($storeLang);
    }

    public function isRtlLanguage(string $languageCode): bool
    {
        return in_array($languageCode, self::RTL_LANGUAGES, true);
    }

    public function shouldProcessFile(string $filePath): bool
    {
        return in_array($filePath, self::CSS_FILE_PATHS, true);
    }
}
