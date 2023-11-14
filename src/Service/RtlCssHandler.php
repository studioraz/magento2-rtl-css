<?php
/*
 * Copyright © 2023 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\RTLCss\Service;

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
     * @param $fileNameWithPath
     * @return Process
     */
    public function executeRtlCssCommand($fileNameWithPath): Process
    {
        $rootDir = $this->getRootDir();

        // Transform the CSS to RTL using the 'rtlcss' command
        /** @var  \Symfony\Component\Process\Process $process */
        $process = $this->processFactory->create([
            'command' => ['npx', 'rtlcss', $fileNameWithPath, $fileNameWithPath],
            'cwd' => $rootDir
        ]);

        $process->run();
        return $process;
    }

    /**
     * @return void
     * @throws \Magento\Framework\Exception\FileSystemException
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
