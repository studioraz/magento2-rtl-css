<?php
/**
 * Copyright © 2023 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace SR\RTLCss\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\View\Page\Config;
use SR\RTLCss\Model\RtlManager;

class ContentDirection implements ObserverInterface
{
    protected Config $pageConfig;
    protected RtlManager $rtlManager;

    public function __construct(
        Config $pageConfig,
        RtlManager $rtlManager
    ) {
        $this->pageConfig = $pageConfig;
        $this->rtlManager = $rtlManager;
    }

    public function execute(Observer $observer): void
    {
        $direction = $this->rtlManager->getStoreViewContentDirection();

        $this->pageConfig->setElementAttribute(
            Config::ELEMENT_TYPE_HTML,
            RtlManager::HTML_ATTRIBUTE_DIR,
            $direction
        );
    }
}
