<?php
/**
 * Detox (https://github.com/dharple/detox/)
 *
 * @link      https://github.com/dharple/detox/
 * @copyright Copyright (c) 2017 Doug Harple
 * @license   https://github.com/dharple/detox/blob/master/LICENSE
 * @since     File available since Release 2.0.0
 */

namespace Detox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Provides the base detox command.
 *
 * @since      Class available since Release 2.0.0
 */
class DetoxCommand extends Command
{

	/**
	 * Configues detox
	 */
	protected function configure()
	{
		$this
			->setName('detox')
			->setDefinition(
				new InputDefinition(array(
					new InputArgument('file', InputArgument::IS_ARRAY,
						'The files to operate on'),

					new InputOption('ascii', null, InputOption::VALUE_NONE,
						'tranlisterate or remove all non-ASCII characters'),

					new InputOption('dry-run', 'n', InputOption::VALUE_NONE,
						'do a dry run (don\'t actually do anything)'),

					new InputOption('help', 'h', InputOption::VALUE_NONE,
						'this message'),

					new InputOption('inline', null, InputOption::VALUE_NONE,
						'run inline mode'),

					new InputOption('lower', null, InputOption::VALUE_NONE,
						'convert filenames to lower case'),

					new InputOption('recursive', 'r', InputOption::VALUE_NONE,
						'be recursive'),

					new InputOption('safe', null, InputOption::VALUE_NONE,
						'convert filenames to command-line safe filenames'),

					new InputOption('special', null, InputOption::VALUE_NONE,
						'work on links and special files'),

					new InputOption('uncgi', null, InputOption::VALUE_NONE,
						'decode CGI-encoded characters in the filename'),

					new InputOption('verbose', 'v', InputOption::VALUE_NONE,
						'be verbose'),

					new InputOption('version', 'V', InputOption::VALUE_NONE,
						'show the current version')

				))
			);
	}

	/**
	 * Runs detox
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln('files to parse:');
		$output->writeln($input->getArgument('file'));
		$output->writeln('');

		$output->writeln('ascii filter ' .
			($input->getOption('ascii') ? 'enabled' : 'disabled'));

		$output->writeln('dry run ' . 
			($input->getOption('dry-run') ? 'enabled' : 'disabled'));

		$output->writeln('inline mode ' . 
			($input->getOption('inline') ? 'enabled' : 'disabled'));

		$output->writeln('lower filter ' . 
			($input->getOption('lower') ? 'enabled' : 'disabled'));

		$output->writeln('recursive mode ' . 
			($input->getOption('recursive') ? 'enabled' : 'disabled'));

		$output->writeln('safe filter ' . 
			($input->getOption('safe') ? 'enabled' : 'disabled'));

		$output->writeln('special file mode ' . 
			($input->getOption('special') ? 'enabled' : 'disabled'));

		$output->writeln('uncgi filter ' . 
			($input->getOption('uncgi') ? 'enabled' : 'disabled'));

		$output->writeln('verbose mode ' . 
			($input->getOption('verbose') ? 'enabled' : 'disabled'));
	}

}
