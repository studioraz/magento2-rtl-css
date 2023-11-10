<?php

namespace SR\RTLCss\Model\Css\Processor;

use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\View\Asset\PreProcessor\Chain;
use Magento\Framework\View\Asset\PreProcessorInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessFactory;
use Magento\Framework\Module\Dir;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\View\Asset\ContextInterface;

class CustomPreprocessor implements PreProcessorInterface
{
    const TMP_RTLCSS_PREPROCESS_DIR = 'rtlcss';

    protected WriteInterface $tmpDir;

    public function __construct(
        private readonly ProcessFactory $processFactory,
        private readonly DirectoryList $directoryList,
        private readonly File $file,
        private readonly Filesystem $filesystem,
        private readonly Dir $moduleDir
    ) {

    }


    /**
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
        $context = $chain->getAsset()->getContext();

        if ($context->getLocale() === 'he_IL' &&
            $context->getAreaCode() == 'frontend'
            && $context->getFilePath() === 'css/styles-m.css'
        ) {

            // Create temporary CSS file under var/rtlcss folder
            $ioAdapter = $this->filesystem->getDirectoryWrite(DirectoryList::VAR_DIR);

            $content = $chain->getContent();

            //var_dump($originalContent); die;
            $ioAdapter->writeFile('rtlcss.css', $content);

            $rootDir = $this->moduleDir->getDir('SR_RTLCss');


            // Transform the CSS to RTL using the 'rtl' command
            /** @var  \Symfony\Component\Process\Process $process */
            $process = $this->processFactory->create([
                'command' => ['npx', 'rtlcss', $ioAdapter->getAbsolutePath('rtlcss.css'), $ioAdapter->getAbsolutePath('rtlcss.css')],
                'cwd' => $rootDir
            ]);

            $process->run();

            if ($process->isSuccessful()) {

                // Get the RTL-converted content from the temporary file
                $rtlContent = $ioAdapter->readFile('rtlcss.css');

                // Set the new RTL content into the chain
                $chain->setContent($rtlContent);

            } else {
                throw new \Exception('RTL conversion failed: ' . $process->getErrorOutput());
            }

            // Delete the temporary file
            //$ioAdapter->delete('rtlcss.css');

        }
    }

    private function getAreaFromPath(string $filename): string
    {
        $area = '';
        $pathParts = explode('/', $filename);
        if (isset($pathParts[0])) {
            $area = $pathParts[0];
        }
        return $area;
    }

}
