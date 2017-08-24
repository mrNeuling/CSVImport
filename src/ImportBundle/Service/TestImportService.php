<?php

namespace ImportBundle\Service;


use AppBundle\Entity\Product;
use AppBundle\Repository\Product\ProductRepository;
use ImportBundle\C;
use ImportBundle\Report\ImportReport;
use ImportBundle\Validator\Validator;

/**
 * Class TestImportService
 * @package ImportBundle\Service
 */
class TestImportService
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var Validator[]
     */
    protected $validators;

    /**
     * @var ImportReport
     */
    protected $report;

    /**
     * TestImportService constructor.
     * @param ProductRepository $productRepository
     * @param ImportReport $report
     */
    public function __construct(ProductRepository $productRepository, ImportReport $report)
    {
        $this->productRepository = $productRepository;
        $this->report            = $report;
        $this->validators        = [];
    }

    /**
     * @param Validator $validator
     * @return $this
     */
    public function addValidator(Validator $validator)
    {
        if ( ! in_array($validator, $this->validators, true)) {
            $this->validators[] = $validator;
        }

        return $this;
    }

    /**
     * @return ImportReport
     */
    public function getReport(): ImportReport
    {
        return $this->report;
    }

    /**
     * @param \Iterator $rows
     */
    public function importRows(\Iterator $rows)
    {
        $totalCount      = iterator_count($rows);
        $successfulCount = 0;

        foreach ($rows as $row) {
            if ($this->validateRow($row)) {
                $this->importRow($row);
                ++$successfulCount;
            } else {
                $this->report->addBadProduct($row);
            }
        }

        $this->report->setTotalCount($totalCount);
        $this->report->setSuccessfulCount($successfulCount);
    }

    /**
     * @param array $row
     */
    public function importRow(array $row)
    {
        $product = $this->productRepository->findByCode($row[C::CSV_INDEX_CODE]);

        if (null === $product) {
            $product = new Product();
            $product->setCode($row[C::CSV_INDEX_CODE]);
            $this->productRepository->add($product);
        }

        $product->setName($row[C::CSV_INDEX_NAME]);
        $product->setDescription($row[C::CSV_INDEX_DESCRIPTION]);
        $product->setAvailableCount((int)$row[C::CSV_INDEX_AVAILABLE_COUNT]);
        $product->setPrice((float)$row[C::CSV_INDEX_PRICE]);

        if ($this->isDiscontinuedRow($row)) {
            $product->setDiscontinuedAt(new \DateTime());
        }
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function validateRow(array $row): bool
    {
        foreach ($this->validators as $validator) {
            if (!$validator->validate($row)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $row
     * @return bool
     */
    protected function isDiscontinuedRow(array $row): bool
    {
        return $row[C::CSV_INDEX_DISCONTINUED] === 'yes';
    }
}