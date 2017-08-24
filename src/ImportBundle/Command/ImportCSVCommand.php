<?php

namespace ImportBundle\Command;


use ImportBundle\C;
use ImportBundle\Report\ImportReport;
use ImportBundle\Report\Reporter;
use AppBundle\Repository\Product\ProductRepository;
use ImportBundle\Service\PersistImportService;
use ImportBundle\Service\TestImportService;
use ImportBundle\Validator\MaxPriceValidator;
use ImportBundle\Validator\MinPriceAndMinStockValidator;
use ImportBundle\Validator\NumericValidator;
use League\Csv\Reader;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ImportCSVCommand
 * @package ImportBundle\Command
 */
class ImportCSVCommand extends ContainerAwareCommand
{
    /**
     * Reporters list.
     *
     * @var Reporter[];
     */
    protected $reporters;

    /**
     * @inheritdoc
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function configure()
    {
        $this
            ->setName('import:csv')
            ->setDescription('Import product items from CSV')
            ->addArgument('file', InputArgument::REQUIRED)
            ->addOption('test', null, InputOption::VALUE_OPTIONAL, 'Activate test mode', false)
            ->addOption('delimiter', null, InputOption::VALUE_OPTIONAL, 'Value delimiter', ',')
            ->addOption('min-price', null, InputOption::VALUE_OPTIONAL, 'Min allowable price')
            ->addOption('max-price', null, InputOption::VALUE_OPTIONAL, 'Max allowable price')
            ->addOption('min-stock', null, InputOption::VALUE_OPTIONAL, 'Min allowable stock count')
            ->addOption('start-offset', null, InputOption::VALUE_OPTIONAL, 'Set starting offset', 1)
        ;
    }

    /**
     * @inheritdoc
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('file');
        if (!is_file($file)) {
            throw new InvalidArgumentException('File \'' . $file . '\' does not exist');
        }

        $container = $this->getContainer();
        $defaultMinPrice  = $container->getParameter('product_min_price');
        $defaultMaxPrice  = $container->getParameter('product_max_price');
        $defaultMinStock  = $container->getParameter('product_min_stock');

        $delimiter = $input->getOption('delimiter');
        $test      = $input->getOption('test');
        $minPrice  = (float)($input->getOption('min-price') ?? $defaultMinPrice);
        $maxPrice  = (float)($input->getOption('max-price') ?? $defaultMaxPrice);
        $minStock  = (int)($input->getOption('min-stock') ?? $defaultMinStock);
        $offset    = (int)$input->getOption('start-offset');

        $test = is_string($test) ? $test === 'true' : (bool)$test;

        $this->reporters = [
            new Reporter\CLIReporter($output),
        ];

        $report        = new ImportReport();
        $entityManager = $container->get('doctrine.orm.entity_manager');

        if ($test) {
            $productRepository = $container->get('repository.product.array');
            $importService     = new TestImportService($productRepository, $report);
        } else {
            /** @var ProductRepository $productRepository */
            $productRepository = $container->get('repository.product.doctrine');
            $importService     = new PersistImportService($productRepository, $report, $entityManager);
        }

        $importService
            ->addValidator(new NumericValidator(C::CSV_INDEX_PRICE))
            ->addValidator(new NumericValidator(C::CSV_INDEX_AVAILABLE_COUNT))
            ->addValidator(new MinPriceAndMinStockValidator($minPrice, $minStock))
            ->addValidator(new MaxPriceValidator($maxPrice))
        ;

        $this->fixLineEndings();

        $reader = Reader::createFromPath($file);
        $reader
            ->setDelimiter($delimiter)
            ->setOffset($offset)
        ;

        $rows = $reader->fetch();

        try {
            $importService->importRows($rows);
            $this->sendReport($report);
        } catch (\Exception $e) {
            $output->writeln($e->getMessage());
        }
    }

    /**
     * Send report conforming to reporters strategy.
     * @param ImportReport $report
     */
    protected function sendReport(ImportReport $report)
    {
        foreach ($this->reporters as $reporter) {
            $reporter->send($report);
        }
    }

    /**
     * Fix line endings for CSV created on MacOS
     */
    protected function fixLineEndings()
    {
        if (!ini_get('auto_detect_line_endings')) {
            ini_set('auto_detect_line_endings', '1');
        }
    }
}