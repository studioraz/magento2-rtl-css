<?php
/*
 * Copyright © 2023 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

namespace SR\RTLCss\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessFactory;
use Magento\Framework\Module\Dir;
class NpmInstallCommand extends  Command

{


    public function __construct(
        private readonly ProcessFactory $processFactory,
        private readonly Dir $moduleDir,
        string           $name = null
    ) {

        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('studioraz:rtl-css:npm-install')
            ->setDescription('Install NPM packages.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Get the root directory of SR_RTLCss module
        $rootDir = $this->moduleDir->getDir('SR_RTLCss');

        /** @var Process $process */
        $process = $this->processFactory->create([
            'command' => ['npm', 'install'],
            'cwd' => $rootDir
        ]);

        //$process->setWorkingDirectory($rootDir);

        $process->run();

        // Print the output of the command to the shell
        $output->writeln($process->getOutput());


        if (!$process->isSuccessful()) {
            $output->writeln($process->getErrorOutput());
            return 1;  // Non-zero exit code to signify that the command failed
        }

        $output->writeln('RTL CSS files generated successfully.');
        return 0;  // Zero exit code to signify that the command was successful
    }

}
