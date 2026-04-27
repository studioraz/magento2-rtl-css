<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

declare(strict_types=1);

namespace SR\RTLCss\Observer;

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Page\Config;
use SR\RTLCss\Service\LocaleWritingDirectionService;

class AddDirAttributeToHtmlElement implements ObserverInterface
{
    private const REGISTRY_FLAG = 'sr_rtlcss_dir_set';

    protected Config $pageConfig;
    protected LocaleWritingDirectionService $rtlManager;
    private Registry $registry;

    public function __construct(
        Config $pageConfig,
        LocaleWritingDirectionService $rtlManager,
        Registry $registry
    ) {
        $this->pageConfig = $pageConfig;
        $this->rtlManager = $rtlManager;
        $this->registry = $registry;
    }

    public function execute(Observer $observer): void
    {
        if ($this->registry->registry(self::REGISTRY_FLAG)) {
            return;
        }
        $this->registry->register(self::REGISTRY_FLAG, true);

        $direction = $this->rtlManager->getStoreViewContentDirection();

        $this->pageConfig->setElementAttribute(
            Config::ELEMENT_TYPE_HTML,
            LocaleWritingDirectionService::HTML_ATTRIBUTE_DIR,
            $direction
        );
    }
}
