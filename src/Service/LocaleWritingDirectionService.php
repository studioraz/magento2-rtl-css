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

    public function __construct(
        protected Resolver $localeResolver
    ) {
    }

    public function getStoreViewContentDirection(): string
    {
        $storeLang = $this->localeResolver->getLocale();

        return $this->getContentDirectionByLanguageCode($storeLang);
    }

    public function isStoreViewContentDirectionRtl(): bool
    {
        $storeLang = $this->localeResolver->getLocale();

        return $this->isContentDirectionRtlByLanguageCode($storeLang);
    }

    public function getContentDirectionByLanguageCode(string $languageCode): string
    {
        $rtlLanguages = $this->getRtlLanguagesList();

        return in_array($languageCode, $rtlLanguages, true) ? self::HTML_ATTRIBUTE_DIR_VALUE_RTL : self::HTML_ATTRIBUTE_DIR_VALUE_LTR;
    }

    public function isContentDirectionRtlByLanguageCode(string $languageCode): bool
    {
        $rtlLanguages = $this->getRtlLanguagesList();

        return in_array($languageCode, $rtlLanguages, true);
    }

    public function getRtlLanguagesList(): array
    {
        return self::RTL_LANGUAGES;
    }
}
