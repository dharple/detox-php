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

use Outsanity\Detox\Filter\Ascii;
use Outsanity\Detox\Filter\Lower;
use Outsanity\Detox\Filter\Safe;
use Outsanity\Detox\Filter\Uncgi;
use Outsanity\Detox\Filter\Wipeup;
use Outsanity\Detox\Sequence;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\ConsoleOutputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Provides the base inline-detox command.
 */
class InlineDetoxCommand extends Command
{

    /**
     * Configures detox
     */
    protected function configure()
    {
        $this
            ->setName('inline-detox')
            ->setDefinition(
                new InputDefinition([
                    new InputArgument(
                        'file',
                        InputArgument::IS_ARRAY,
                        'the files to operate on'
                    ),

                    new InputOption(
                        'ascii',
                        null,
                        InputOption::VALUE_NONE,
                        'tranlisterate or remove all non-ASCII characters'
                    ),

                    new InputOption(
                        'lower',
                        null,
                        InputOption::VALUE_NONE,
                        'convert filenames to lower case'
                    ),

                    new InputOption(
                        'safe',
                        null,
                        InputOption::VALUE_NONE,
                        'convert filenames to command-line safe filenames'
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
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function debugOptions(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle(
            $input,
            ($output instanceof ConsoleOutputInterface) ? $output->getErrorOutput() : $output
        );

        $io->text('files to parse:');
        $io->listing($input->getArgument('file'));

        $io->table(
            ['feature', 'selected'],
            [
                ['ascii filter', $input->getOption('ascii') ? 'y' : 'n'],
                ['lower filter', $input->getOption('lower') ? 'y' : 'n'],
                ['safe filter', $input->getOption('safe') ? 'y' : 'n'],
                ['uncgi filter', $input->getOption('uncgi') ? 'y' : 'n'],
                ['verbose mode', $input->getOption('verbose') ? 'y' : 'n'],
                ['wipeup filter', $input->getOption('wipeup') ? 'y' : 'n'],
            ]
        );
    }

    /**
     * Runs inline-detox
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if ($output->getVerbosity() == OutputInterface::VERBOSITY_DEBUG) {
            $this->debugOptions($input, $output);
        }

        $io = new SymfonyStyle($input, $output);

        $sequence = new Sequence();

        if ($input->getOption('uncgi')) {
            $sequence->addFilter(new Uncgi());
        }

        if ($input->getOption('ascii')) {
            $sequence->addFilter(new Ascii());
        }

        if ($input->getOption('lower')) {
            $sequence->addFilter(new Lower());
        }

        if ($input->getOption('safe')) {
            $sequence->addFilter(new Safe());
        }

        if ($input->getOption('wipeup')) {
            $sequence->addFilter(new Wipeup());
        }

        while ($line = fgets(STDIN, PHP_MAXPATHLEN + 2)) {
            print($sequence->filter($line));
        }

        return 0;
    }
}
