<?php

/**
 * Copyright © 2026 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace SR\RTLCss\Plugin\Framework\View\Result;

use Magento\Framework\App\ResponseInterface;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Result\Page;
use SR\RTLCss\Service\LocaleWritingDirectionService;

/**
 * Sets the <html dir="..."> attribute on full pages only.
 *
 * Done right before rendering, when the layout is already built: Page\Config::setElementAttribute() builds the page
 * config, and calling it during the layout build (layout_generate_blocks_before) re-enters the build on non-page
 * results (Result\Layout), which then fails with 'An element with a "root" ID already exists.'.
 */
class PagePlugin
{
    private Config $pageConfig;
    private LocaleWritingDirectionService $rtlManager;

    public function __construct(
        Config $pageConfig,
        LocaleWritingDirectionService $rtlManager,
    ) {
        $this->pageConfig = $pageConfig;
        $this->rtlManager = $rtlManager;
    }

    /**
     * @param Page $subject
     * @param ResponseInterface $response
     * @return null
     */
    public function beforeRenderResult(Page $subject, ResponseInterface $response)
    {
        $this->pageConfig->setElementAttribute(
            Config::ELEMENT_TYPE_HTML,
            LocaleWritingDirectionService::HTML_ATTRIBUTE_DIR,
            $this->rtlManager->getStoreViewContentDirection(),
        );

        return null;
    }
}
