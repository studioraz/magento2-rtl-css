<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

namespace SR\RTLCss\Model\Css\Processor;

use Exception;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\View\Asset\ContextInterface;
use Magento\Framework\View\Asset\PreProcessor\Chain;
use Magento\Framework\View\Asset\PreProcessorInterface;
use SR\RTLCss\Service\LocaleWritingDirectionService;
use SR\RTLCss\Service\RtlCssHandler;

class RtlCssPreprocessor implements PreProcessorInterface
{

    /**
     * @param RtlCssHandler $rtlCssHandler
     */
    public function __construct(
        private readonly RtlCssHandler $rtlCssHandler,
        private readonly LocaleWritingDirectionService $localeWritingDirectionService
    ) {
    }

    /**
     * @param Chain $chain
     *
     * @return void
     *
     * @throws FileSystemException
     * @throws Exception
     */
    public function process(Chain $chain): void
    {
        /**
         * context
         * 'baseUrl' => 'https://ddev-magento2.ddev.site/static/version1697963378/',
         * 'baseDir' => 'static',
         * 'path' => 'frontend/Magento/luma/en_US',
         * 'area' => 'frontend',
         * 'theme' => 'Magento/luma',
         * 'locale' => 'en_US',
         *
         */

        /**
         * asset
         *  filePath => css/styles-m.css
         *  module => ""
         *  contentType => "css"
         *  context =>
         *  source =>
         *  resolvedFile =>
         *  minification =>
         *  sourceContentType =>
         */

        // $chain->getTargetAssetPath() => frontend/Magento/luma/en_US/css/styles-m.css
        // $chain->getAsset()->getFilePath() => css/styles-m.css
        // $chain->getAsset()->getContext()->getLocale() => en_US
        // $chain->getAsset()->getContext()->getAreaCode() => frontend

        /** @var  ContextInterface $context */
        $asset = $chain->getAsset();
        $context = $asset->getContext();

        $this->rtlCssHandler->validateIsRtlCssInstalled();

        if ($this->shouldProcessFile($context, $asset->getFilePath())) {
            $content = $chain->getContent();
            $process = $this->rtlCssHandler->executeRtlCssCommand($content);

            if ($process->isSuccessful()) {
                // Get the RTL-converted content from the process output
                $rtlContent = $process->getOutput();

                // Set the new RTL content into the chain
                $chain->setContent($rtlContent);
            } else {
                throw new Exception('RTL conversion failed: ' . $process->getErrorOutput());
            }
        }
    }

    /**
     * Determines if the current asset should be processed for RTL.
     *
     * @param ContextInterface $context
     * @param string $cssFilePath
     * @return bool
     */
    public function shouldProcessFile(\ContextInterface $context, string $cssFilePath): bool
    {
        return $context->getAreaCode() === 'frontend'
            && $this->localeWritingDirectionService->isRtlLanguage($context->getLocale())
            && $this->localeWritingDirectionService->shouldProcessFile($cssFilePath);
    }
}
