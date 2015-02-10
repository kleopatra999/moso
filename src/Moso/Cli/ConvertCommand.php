<?php

namespace Behance\Moso\Cli;

use Behance\Moso\Collectors\CloverCollector as Clover;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class ConvertCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
        ->setName('convert')
        ->setDescription('Converts given report files of a given type into a JSON blob recognizable by Coveralls.')
        ->setDefinition([
            new InputOption(
                'format',
                'f',
                InputOption::VALUE_OPTIONAL,
                'Specify the input report file type.',
                'clover'
            ),
            new InputOption(
                'output-file',
                'o',
                InputOption::VALUE_OPTIONAL,
                'File to output JSON to.',
                './coverage.json'
            ),
            new InputArgument(
                'reports',
                InputArgument::REQUIRED | InputArgument::IS_ARRAY,
                'Report files.'
            ),
        ])
        ->setHelp(<<<EOT
The <info>convert</info> command takes a given report file (or a set of report files) of a given, known type and converts
them into a single JSON blob.

<comment>moso convert report1.xml report2.xml report3.xml</comment>
EOT
        );
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $collector = $this->getCollector($input->getOption('format'));
        $collector->gatherFiles($input->getArgument('reports'));

        $paths = implode(', ', $collector->files());
        $output->writeln("Merging report(s): {$paths}");

        return $collector->writeMergedResult($input->getOption('output-file'));
    }

    /**
     * @return CollectorInterface
     */
    protected function getCollector($format)
    {
        $collectors = [ 'clover' => new Clover() ];

        return $collectors[$format];
    }
}
