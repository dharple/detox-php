<?php

/**
 * This file is part of the Detox package.
 *
 * (c) Doug Harple <detox.dharple@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Outsanity\Detox\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Provides the base detox command.
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
                new InputDefinition([
                    new InputArgument(
                        'path',
                        InputArgument::IS_ARRAY,
                        'the files or directories to operate on'
                    ),

                    new InputOption(
                        'ascii',
                        null,
                        InputOption::VALUE_NONE,
                        'tranlisterate or remove all non-ASCII characters'
                    ),

                    new InputOption(
                        'dry-run',
                        null,
                        InputOption::VALUE_NONE,
                        'do a dry run (don\'t actually do anything)'
                    ),

                    new InputOption(
                        'inline',
                        null,
                        InputOption::VALUE_NONE,
                        'run inline mode'
                    ),

                    new InputOption(
                        'lower',
                        null,
                        InputOption::VALUE_NONE,
                        'convert filenames to lower case'
                    ),

                    new InputOption(
                        'recursive',
                        'r',
                        InputOption::VALUE_NONE,
                        'be recursive'
                    ),

                    new InputOption(
                        'safe',
                        null,
                        InputOption::VALUE_NONE,
                        'convert filenames to command-line safe filenames'
                    ),

                    new InputOption(
                        'special',
                        null,
                        InputOption::VALUE_NONE,
                        'work on links and special files'
                    ),

                    new InputOption(
                        'uncgi',
                        null,
                        InputOption::VALUE_NONE,
                        'decode CGI-encoded characters in the filename'
                    ),

                    new InputOption(
                        'wipeup',
                        null,
                        InputOption::VALUE_NONE,
                        'remove duplicate - and _ characters'
                    ),
                ])
            );
    }

    /**
     * Dumps option and argument information to stderr.
     */
    protected function debugOptions(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle(
            $input,
            ($output instanceof ConsoleOutputInterface) ? $output->getErrorOutput() : $output
        );

        $io->text('files and directories to parse:');
        $io->listing($input->getArgument('path'));

        $io->table(
            ['feature', 'selected'],
            [
                ['ascii filter', $input->getOption('ascii') ? 'y' : 'n'],
                ['dry run', $input->getOption('dry-run') ? 'y' : 'n'],
                ['inline mode', $input->getOption('inline') ? 'y' : 'n'],
                ['lower filter', $input->getOption('lower') ? 'y' : 'n'],
                ['recursive mode', $input->getOption('recursive') ? 'y' : 'n'],
                ['safe filter', $input->getOption('safe') ? 'y' : 'n'],
                ['special file mode', $input->getOption('special') ? 'y' : 'n'],
                ['uncgi filter', $input->getOption('uncgi') ? 'y' : 'n'],
                ['verbose mode', $input->getOption('verbose') ? 'y' : 'n'],
                ['wipeup filter', $input->getOption('wipeup') ? 'y' : 'n'],
            ]
        );
    }

    /**
     * Runs detox
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $this->debugOptions($input, $output);
        }

        $io = new SymfonyStyle($input, $output);

        if (count($input->getArgument('path')) == 0) {
            $io->error('please specify at least one file or path to operate upon');
            $io->text($this->getSynopsis());
            return 1;
        }

        return 0;
    }
}
