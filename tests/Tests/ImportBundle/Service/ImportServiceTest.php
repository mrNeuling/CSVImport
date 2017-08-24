<?php

namespace Tests\ImportBundle\Service;


use AppBundle\Entity\Product;
use ImportBundle\Report\ImportReport;
use ImportBundle\Repository\Product\ArrayProductRepository;
use ImportBundle\Service\TestImportService;
use ImportBundle\Validator\MaxPriceValidator;
use ImportBundle\Validator\MinPriceAndMinStockValidator;
use ImportBundle\Validator\NumericValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class ImportServiceTest
 * @package Tests\ImportBundle\Service
 */
class ImportServiceTest extends TestCase
{
    /**
     * @return \ArrayIterator
     */
    public function getTestData(): \ArrayIterator
    {
        return new \ArrayIterator([
            ['P0001', 'TV',            '32” Tv',                               '10',                   '399.99',  ''],
            ['P0002', 'Cd Player',     'Nice CD player',                       '11',                   '50.12',   'yes'],
            ['P0003', 'VCR',           'Top notch VCR',                        '12',                   '39.33',   'yes'],
            ['P0004', 'Bluray Player', 'Watch it in HD',                       '1',                    '24.55',   ''],
            ['P0005', 'XBOX360',       'Best.console.ever',                    '5',                    '30.44',   ''],
            ['P0006', 'PS3',           'Mind your details',                    '3',                    '24.99',   ''],
            ['P0007', '24” Monitor',   'Awesome',                              '',                     '35.99',   ''],
            ['P0008', 'CPU',           'Speedy',                               '12',                   '25.43',   ''],
            ['P0009', 'Harddisk',      'Great for storing data',               '0',                    '99.99',   ''],
            ['P0010', 'CD Bundle',     'Lots of fun',                          '0',                    '10',      ''],
            ['P0011', 'Misc Cables',   'error in export'],
            ['P0012', 'TV',            'HD ready',                             '45',                   '50.55',   ''],
            ['P0013', 'Cd Player',     'Beats MP3',                            '34',                   '27.99',   ''],
            ['P0014', 'VCR',           'VHS rules',                            '3',                    '23',      'yes'],
            ['P0015', 'Bluray Player', 'Excellent picture',                    '32',                   '$4.33',   ''],
            ['P0015', 'Bluray Player', 'Excellent picture',                    '32',                   '4.33',    ''],
            ['P0016', '24” Monitor',   'Visual candy',                         '3',                    '45',      ''],
            ['P0017', 'CPU',           'Processing power',                     'ideal for multimedia', '4',       '4.22', ''],
            ['P0018', 'Harddisk',      'More storage options',                 '34',                   '50',      'yes'],
            ['P0019', 'CD Bundle',     'Store all your data. Very convenient', '23',                   '3.44',    ''],
            ['P0020', 'Cd Player',     'Play CD\'s',                           '56',                   '30',      ''],
            ['P0021', 'VCR',           'Watch all those retro videos',         '12',                   '3.55',    'yes'],
            ['P0022', 'Bluray Player', 'The future of home entertainment!',    '45',                   '3',       ''],
            ['P0023', 'XBOX360',       'Amazing',                              '23',                   '50',      ''],
            ['P0024', 'PS3',           'Just don\'t go online',                '22',                   '24.33',   'yes'],
            ['P0025', 'TV',            'Great for television',                 '21',                   '40',      ''],
            ['P0026', 'Cd Player',     'A personal favourite',                 '0',                    '34.55',   ''],
            ['P0027', 'VCR',           'Plays videos',                         '34',                   '1200.03', 'yes'],
            ['P0028', 'Bluray Player', 'Plays bluray\'s',                      '32',                   '1100.04', 'yes'],
        ]);
    }

    /**
     * Test import
     */
    public function testImport()
    {
        $productRepo   = new ArrayProductRepository();
        $report        = new ImportReport();
        $importService = new TestImportService($productRepo, $report);
        $data          = $this->getTestData();

        $importService
            ->addValidator(new NumericValidator(4))
            ->addValidator(new NumericValidator(3))
            ->addValidator(new MinPriceAndMinStockValidator(5, 10))
            ->addValidator(new MaxPriceValidator(1000))
        ;

        $importService->importRows($data);

        $product = $productRepo->findByCode('P0001');

        $this->assertEquals(29, $report->getTotalCount());
        $this->assertEquals(23, $report->getSuccessfulCount());
        $this->assertEquals(6, $report->getSkippedCount());

        $this->assertInstanceOf(Product::class, $product);
        $this->assertEquals('TV', $product->getName());
        $this->assertEquals('32” Tv', $product->getDescription());
        $this->assertEquals(10, $product->getAvailableCount());
        $this->assertEquals(399.99, $product->getPrice());
    }
}