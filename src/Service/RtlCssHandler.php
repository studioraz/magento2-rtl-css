<?php
/**
 * Copyright © 2025 Studio Raz. All rights reserved.
 * See LICENSE for license details.
 */

namespace SR\RTLCss\Service;

use Magento\Framework\Exception\FileSystemException;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessFactory;
use Magento\Framework\Module\Dir;
use Magento\Framework\Filesystem\Driver\File;

class RtlCssHandler
{
    public const RTL_FILE_PATH = 'node_modules/.bin/rtlcss';

    protected $rootDir;

    /**
     * @param ProcessFactory $processFactory
     * @param Dir $moduleDir
     * @param File $file
     */
    public function __construct(
        private readonly ProcessFactory $processFactory,
        private readonly Dir $moduleDir,
        private readonly File $file
    ) {
    }

    /**
     * @param string $cssContent
     *
     * @return Process
     */
    public function executeRtlCssCommand($cssContent): Process
    {
        $rootDir = $this->getRootDir();

        // Transform the CSS to RTL using the 'rtlcss' command
        /** @var  Process $process */
        $process = $this->processFactory->create([
            'command' => ['npx', 'rtlcss', '--stdin'],
            'cwd' => $rootDir
        ]);

        $process->setInput($cssContent);
        $process->mustRun();

        return $process;
    }

    /**
     * @return void
     * @throws FileSystemException
     */
    public function validateIsRtlCssInstalled()
    {
        $rootDir = $this->getRootDir();
        if (!$this->file->isExists($rootDir . DIRECTORY_SEPARATOR . self::RTL_FILE_PATH)) {
            $this->installRtlCss();
        }
    }

    /**
     * @return void
     */
    protected function installRtlCss()
    {
        $rootDir = $this->getRootDir();

        /** @var Process $process */
        $process = $this->processFactory->create([
            'command' => ['npm', 'install'],
            'cwd' => $rootDir
        ]);

        $process->run();
    }

    /**
     * @return string
     */
    protected function getRootDir(): string
    {
        if (!$this->rootDir) {
            $this->rootDir = $this->moduleDir->getDir('SR_RTLCss');
        }
        return $this->rootDir;
    }
}
