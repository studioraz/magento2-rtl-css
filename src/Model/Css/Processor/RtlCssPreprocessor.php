<?php
/*
 * Copyright © 2023 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\RTLCss\Model\Css\Processor;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\View\Asset\PreProcessor\Chain;
use Magento\Framework\View\Asset\PreProcessorInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Asset\ContextInterface;
use SR\RTLCss\Service\RtlCssHandler;

class RtlCssPreprocessor implements PreProcessorInterface
{
    public const TMP_RTLCSS_PREPROCESS_DIR = 'rtlcss';

    // INFO: commented out style{.min}.css for exclude Hyva tailwind styles from RTL CSS
    protected $cssFileNames = [
        'css/styles-m.css',
        'css/styles-m.min.css',
        'css/styles-l.css',
        'css/styles-l.min.css',
//        'css/styles.css',
//        'css/styles.min.css',
        'css/email.css'
    ];

    /**
     * @param Filesystem $filesystem
     * @param RtlCssHandler $rtlCssHandler
     */
    public function __construct(
        private readonly Filesystem $filesystem,
        private readonly RtlCssHandler $rtlCssHandler
    ) {
    }

    /**
     * @param Chain $chain
     * @return void
     * @throws FileSystemException
     */
    public function process(Chain $chain)
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

        if ($context->getLocale() === 'he_IL' &&
            $context->getAreaCode() == 'frontend' &&
            $this->isNeedToRtl($asset->getFilePath())
        ) {
            $content = $chain->getContent();
            $fileNameWithPath = self::TMP_RTLCSS_PREPROCESS_DIR . DIRECTORY_SEPARATOR . $this->createFileName();
            $ioAdapter = $this->writeCssToFile($fileNameWithPath, $content);
            $process = $this->rtlCssHandler->executeRtlCssCommand($ioAdapter->getAbsolutePath($fileNameWithPath));

            if ($process->isSuccessful()) {
                // Get the RTL-converted content from the temporary file
                $rtlContent = $ioAdapter->readFile($fileNameWithPath);
                // Set the new RTL content into the chain
                $chain->setContent($rtlContent);
            } else {
                throw new \Exception('RTL conversion failed: ' . $process->getErrorOutput());
            }

            try {
                // Delete the temporary file
                $ioAdapter->delete($fileNameWithPath);
            } catch (FileSystemException $e) {}
        }
    }

    /**
     * @param string $filename
     * @param string $content
     * @return WriteInterface
     */
    protected function writeCssToFile(string $filename, string $content): WriteInterface
    {
        // Create temporary CSS file under var/rtlcss directory
        try {
            $ioAdapter = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);
            $ioAdapter->create(self::TMP_RTLCSS_PREPROCESS_DIR);
            $ioAdapter->writeFile($filename, $content);
        } catch (\Exception | FileSystemException $e) {}
        return $ioAdapter;
    }

    /**
     * @return string
     */
    protected function createFileName(): string
    {
        $time = time();
        return 'rtlcss-' . $time . '.css';
    }

    /**
     * @param $currentCssFileName
     * @return bool
     */
    protected function isNeedToRtl($currentCssFileName): bool
    {
        return in_array($currentCssFileName, $this->cssFileNames);
    }
}
