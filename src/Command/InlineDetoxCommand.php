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
 * Provides the base inline-detox command.
 *
 * @since      Class available since Release 2.0.0
 */
class InlineDetoxCommand extends Command
{

	/**
	 * Configues detox
	 */
	protected function configure()
	{
		$this
			->setName('inline-detox')
			->setDefinition(
				new InputDefinition(array(
					new InputArgument('file', InputArgument::IS_ARRAY,
						'the files to operate on'),

					new InputOption('ascii', null, InputOption::VALUE_NONE,
						'tranlisterate or remove all non-ASCII characters'),

					new InputOption('color', '', InputOption::VALUE_NONE,
						'enable colors'),

					new InputOption('help', 'h', InputOption::VALUE_NONE,
						'this message'),

					new InputOption('lower', null, InputOption::VALUE_NONE,
						'convert filenames to lower case'),

					new InputOption('safe', null, InputOption::VALUE_NONE,
						'convert filenames to command-line safe filenames'),

					new InputOption('uncgi', null, InputOption::VALUE_NONE,
						'decode CGI-encoded characters in the filename'),

					new InputOption('verbose', 'v|vv|vvv', InputOption::VALUE_NONE,
						'be verbose'),

					new InputOption('version', 'V', InputOption::VALUE_NONE,
						'show the current version'),

					new InputOption('wipeup', null, InputOption::VALUE_NONE,
						'remove duplicate - and _ characters'),

				))
			);
	}

	/**
	 * Dumps option and argument information to stderr.
	 */
	protected function debugOptions(InputInterface $input, OutputInterface $output)
	{
		$io = new SymfonyStyle($input, $output->getErrorOutput());

		$io->text('files to parse:');
		$io->listing($input->getArgument('file'));

		$io->table(
			array('feature', 'selected'),
			array(
				array('ascii filter', $input->getOption('ascii') ? 'y' : 'n'),
				array('lower filter', $input->getOption('lower') ? 'y' : 'n'),
				array('safe filter', $input->getOption('safe') ? 'y' : 'n'),
				array('uncgi filter', $input->getOption('uncgi') ? 'y' : 'n'),
				array('verbose mode', $input->getOption('verbose') ? 'y' : 'n'),
				array('wipeup filter', $input->getOption('wipeup') ? 'y' : 'n'),
			)
		);
	}

	/**
	 * Runs inline-detox
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
			$this->debugOptions($input, $output);
		}

		$io = new SymfonyStyle($input, $output);

		$sequence = new \Detox\Sequence();

		if ($input->getOption('uncgi')) {
			$sequence->addFilter(new \Detox\Filter\Uncgi());
		}

		if ($input->getOption('ascii')) {
			$sequence->addFilter(new \Detox\Filter\Ascii());
		}

		if ($input->getOption('lower')) {
			$sequence->addFilter(new \Detox\Filter\Lower());
		}

		if ($input->getOption('safe')) {
			$sequence->addFilter(new \Detox\Filter\Safe());
		}

		if ($input->getOption('wipeup')) {
			$sequence->addFilter(new \Detox\Filter\Wipeup());
		}

		while($line = fgets(STDIN, PHP_MAXPATHLEN + 2)) {
			print($sequence->filter($line));
		}

	}

}

