<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace SR\RTLCss\Plugin\Hyva\Theme\ViewModel;

use Hyva\Theme\ViewModel\SvgIcons;
use SR\RTLCss\Service\LocaleWritingDirectionService;

class SvgIconsPlugin
{
    public function __construct(
        protected LocaleWritingDirectionService $rtlManager
    ) {
    }

    public function beforeRenderHtml(
        SvgIcons $subject,
        string $icon,
        string $classNames = '',
        ?int $width = 24,
        ?int $height = 24,
        array $attributes = []
    ): array {
        if ($this->isApplicable($icon, $attributes) && $rtlIcon = $this->getRtlIconName($icon)) {
            $icon = $rtlIcon;
        }

        return [$icon, $classNames, $width, $height, $attributes];
    }

    public function isApplicable(string $icon, array $attributes): bool
    {
        if (!$this->rtlManager->isStoreViewContentDirectionRtl()) {
            return false;
        }

        if (!str_contains($icon, 'left') && !str_contains($icon, 'right')) {
            return false;
        }

        return true;
    }

    public function getRtlIconName($iconName): ?string
    {
        return match (true) {
            str_contains($iconName, 'left') => str_replace('left', 'right', $iconName),
            str_contains($iconName, 'right') => str_replace('right', 'left', $iconName),
            default => null,
        };
    }
}
