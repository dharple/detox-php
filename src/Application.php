<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Detox;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Overrides a few methods on the default Symfony application.
 *
 * @since      Class available since Release 2.0.0
 */
class Application
	extends \Symfony\Component\Console\Application
{

    /**
	 * Forces the application to non-ANSI only.
     *
     * @param InputInterface  $input  An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     */
    protected function configureIO(InputInterface $input,
		OutputInterface $output)
    {
		parent::configureIO($input, $output);

		$output->setDecorated(
			$input->hasParameterOption(array('--color'), true));
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
