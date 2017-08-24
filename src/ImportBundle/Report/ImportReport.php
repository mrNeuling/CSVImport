<?php

namespace ImportBundle\Report;


use AppBundle\Entity\Product;

/**
 * Class ImportReport
 * @package ImportBundle\Report
 */
class ImportReport
{
    /**
     * Total count of processed products
     * @var int
     */
    protected $totalCount;

    /**
     * Count of products processed successful
     * @var int
     */
    protected $successfulCount;

    /**
     * Items which fail to be inserted correctly
     * @var array[]
     */
    protected $badProducts;

    /**
     * ImportReport constructor.
     */
    public function __construct()
    {
        $this->totalCount      = 0;
        $this->successfulCount = 0;
        $this->badProducts     = [];
    }

    /**
     * @return int
     */
    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    /**
     * @return int
     */
    public function getSuccessfulCount(): int
    {
        return $this->successfulCount;
    }

    /**
     * @return Product[]
     */
    public function getBadProducts(): array
    {
        return $this->badProducts;
    }

    /**
     * @return int
     */
    public function getSkippedCount(): int
    {
        return $this->totalCount - $this->successfulCount;
    }

    /**
     * @param int $totalCount
     */
    public function setTotalCount(int $totalCount)
    {
        $this->totalCount = $totalCount;
    }

    /**
     * @param int $successfulCount
     */
    public function setSuccessfulCount(int $successfulCount)
    {
        $this->successfulCount = $successfulCount;
    }

    /**
     * @param array $product
     */
    public function addBadProduct(array $product)
    {
        $this->badProducts[] = $product;
    }
}