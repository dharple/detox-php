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
use Symfony\Component\Console\Style\SymfonyStyle;

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
					new InputArgument('path', InputArgument::IS_ARRAY,
						'the files or directories to operate on'),

					new InputOption('ascii', null, InputOption::VALUE_NONE,
						'tranlisterate or remove all non-ASCII characters'),

					new InputOption('--color', '', InputOption::VALUE_NONE,
						'enable colors'),

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
		$io = new SymfonyStyle($input, $output);

		if (count($input->getArgument('path')) == 0) {
			$io->error('please specify at least one file or path to operate upon');
			$io->text($this->getSynopsis());
			return;
		}

		$io->text('files and directories to parse:');
		$io->listing($input->getArgument('path'));

		$io->table(
			array('feature', 'selected'),
			array(
				array('ascii filter', $input->getOption('ascii') ? 'y' : 'n'),
				array('dry run', $input->getOption('dry-run') ? 'y' : 'n'),
				array('inline mode', $input->getOption('inline') ? 'y' : 'n'),
				array('lower filter', $input->getOption('lower') ? 'y' : 'n'),
				array('recursive mode', $input->getOption('recursive') ? 'y' : 'n'),
				array('safe filter', $input->getOption('safe') ? 'y' : 'n'),
				array('special file mode', $input->getOption('special') ? 'y' : 'n'),
				array('uncgi filter', $input->getOption('uncgi') ? 'y' : 'n'),
				array('verbose mode', $input->getOption('verbose') ? 'y' : 'n'),
			)
		);

	}

}

// vim:ai:cin:noet:ts=4:sw=4:fo+=or
