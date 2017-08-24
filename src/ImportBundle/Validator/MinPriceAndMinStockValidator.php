<?php

namespace ImportBundle\Validator;


use ImportBundle\C;

/**
 * Class MinPriceAndMinStockValidator
 * @package ImportBundle\Validator
 */
class MinPriceAndMinStockValidator implements Validator
{
    /**
     * @var int|string
     */
    private $priceIndex;

    /**
     * @var int|string
     */
    private $stockIndex;

    /**
     * @var float
     */
    private $minPrice;

    /**
     * @var int
     */
    private $minStock;

    /**
     * MinPriceAndMinStockValidator constructor.
     * @param float $minPrice
     * @param int $minStock
     * @param int|string $priceIndex
     * @param int|string $stockIndex
     */
    public function __construct(float $minPrice, int $minStock, $priceIndex = C::CSV_INDEX_PRICE, $stockIndex = C::CSV_INDEX_AVAILABLE_COUNT)
    {
        $this->minPrice   = $minPrice;
        $this->minStock   = $minStock;
        $this->priceIndex = $priceIndex;
        $this->stockIndex = $stockIndex;
    }

    /**
     * @inheritdoc
     */
    public function validate(array $row): bool
    {
        return
            isset($row[$this->priceIndex], $row[$this->stockIndex]) &&
            (
                (float)$row[$this->priceIndex] >= $this->minPrice ||
                (int)$row[$this->stockIndex] >= $this->minStock
            );
    }
}