<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace SR\RTLCss\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use SR\RTLCss\Service\LocaleWritingDirectionService;

class AddDirAttributeToHtmlElement implements ObserverInterface
{
    protected Config $pageConfig;
    protected LocaleWritingDirectionService $rtlManager;

    public function __construct(
        Config $pageConfig,
        LocaleWritingDirectionService $rtlManager
    ) {
        $this->pageConfig = $pageConfig;
        $this->rtlManager = $rtlManager;
    }

    public function execute(Observer $observer): void
    {
        $direction = $this->rtlManager->getStoreViewContentDirection();

        $this->pageConfig->setElementAttribute(
            Config::ELEMENT_TYPE_HTML,
            LocaleWritingDirectionService::HTML_ATTRIBUTE_DIR,
            $direction
        );
    }
}
