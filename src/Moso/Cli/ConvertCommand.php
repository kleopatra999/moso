<?php

namespace Behance\Moso\Cli;

use Behance\Moso\Formatters\FormatterInterface;
use Behance\Moso\Formatters\CoverallsFormatter as Coveralls;
use Behance\Moso\Collectors\CloverCollector as Clover;
use Behance\Moso\Collectors\CollectorInterface;

use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Command\Command;

class ConvertCommand extends Command {

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
                'output-format',
                'u',
                InputOption::VALUE_OPTIONAL,
                'Specify the input report file type.',
                'coveralls'
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
                InputArgument::REQUIRED,
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

  protected function execute(InputInterface $input, OutputInterface $output) {
      $interactive = !$input->getOption('no-interactive');

      $in_format = $input->getOption('format');
      $reports = $input->getArgument('reports');
      $report = (count($reports) === 1)
                ? array_pop($reports)
                : $this->mergeReports(
                      $this->getFormatter($in_format),
                      $output,
                      $reports,
                      $interactive
                  );

      if (is_null($report)) {
        $output->writeln('Failed to write or evaluate report file(s)');
        return 1;
      }

      $out_format = $input->getOption('output-format');
      $formatter = $this->getFormatter($out_format);

      return $this->writeResult($formatter, $input->getOption('output-file'));
  }

  private function writeResult(FormatterInterface $formatter, OutputInterface $output, $outfile) {
      if (strlen($outfile) > 1) {
        return $formatter->dumpFile($outfile);
      }

      return $output->writeln($formatter->rawOutput());
  }

  private function mergeReports(CollectorInterface $collector, OutputInterface $output, $paths, $interactive) {
      $collector->gatherFiles($paths);

      $merge_report = sha1(implode('', $collector->files()));

      if ($interactive && !$this->getHelper('question')->ask("Merging reports into {$merge_report}, ok?")) {
        return NULL;
      }

      $output->writeln("Merging reports into {$merge_report} ...");

      return $collector->writeMergedResult($merge_report);
  }

  protected function getFormatter($type) {
    $formatters = [
      'coveralls' => new CoverallsFormatter()
    ];

    return $formatters[$type];
  }

  protected function getCollector($format) {
    $collectors = [
        'clover' => new Clover()
    ];

    return $collectors[$format];
  }
}
