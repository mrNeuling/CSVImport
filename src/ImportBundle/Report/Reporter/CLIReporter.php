<?php

namespace ImportBundle\Report\Reporter;


use ImportBundle\Report\ImportReport;
use ImportBundle\Report\Reporter;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CLIReporter
 * @package ImportBundle\Report\Reporter
 */
class CLIReporter implements Reporter
{
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * CLIReporter constructor.
     * @param OutputInterface $output
     */
    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    /**
     * @inheritdoc
     */
    public function send(ImportReport $report)
    {
        $this->output->writeln('Total count: ' . $report->getTotalCount());
        $this->output->writeln('Successful: ' . $report->getSuccessfulCount());
        $this->output->writeln('Skipped: ' . $report->getSkippedCount());

        $this->output->writeln('Skipped products:');
        foreach ($report->getBadProducts() as $product) {
            $this->output->writeln(implode(',', $product));
        }
    }
}