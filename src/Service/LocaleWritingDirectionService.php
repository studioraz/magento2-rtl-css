<?php
/**
 * Copyright © 2024 Studio Raz. All rights reserved.
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

    protected Resolver $localeResolver;

    public function __construct(
        Resolver $localeResolver
    ) {
        $this->localeResolver = $localeResolver;
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

        return in_array($languageCode, $rtlLanguages) ? self::HTML_ATTRIBUTE_DIR_VALUE_RTL : self::HTML_ATTRIBUTE_DIR_VALUE_LTR;
    }

    public function isContentDirectionRtlByLanguageCode(string $languageCode): bool
    {
        return $this->getContentDirectionByLanguageCode($languageCode) === self::HTML_ATTRIBUTE_DIR_VALUE_RTL;
    }

    public function getRtlLanguagesList(): array
    {
        return [
            'ar_SA',
            'arc_SY',
            'dv_MV',
            'fa_IR',
            'he_IL',
            'ku_IQ',
            'ps_AF',
            'sam_IL',
            'tzm_MA',
            'ug_CN',
            'ur_PK',
            'yi_IL'
        ];
    }
}
