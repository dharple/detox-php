<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Detox;

use Symfony\Component\Console\Application as BaseApplication;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Overrides a few methods on the default Symfony application.
 */
class Application extends BaseApplication
{

    /**
     * Forces the application to non-ANSI only.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function configureIO(
        InputInterface $input,
        OutputInterface $output
    ) {
        parent::configureIO($input, $output);

        $output->setDecorated(
            $input->hasParameterOption(['--color'], true)
        );
    }

    /**
     * Disables the default options.
     *
     * @return InputDefinition An InputDefinition instance
     */
    protected function getDefaultInputDefinition()
    {
        return new InputDefinition();
    }
}
