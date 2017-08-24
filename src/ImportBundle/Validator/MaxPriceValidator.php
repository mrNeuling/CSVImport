<?php

namespace ImportBundle\Validator;


use ImportBundle\C;

/**
 * Class MaxPriceValidator
 * @package ImportBundle\Validator
 */
class MaxPriceValidator implements Validator
{
    /**
     * @var int|string
     */
    private $priceIndex;

    /**
     * @var float
     */
    private $maxPrice;

    /**
     * MaxPriceValidator constructor.
     * @param float $maxPrice
     * @param int|string $priceIndex
     */
    public function __construct(float $maxPrice, $priceIndex = C::CSV_INDEX_PRICE)
    {
        $this->maxPrice = $maxPrice;
        $this->priceIndex = $priceIndex;
    }

    /**
     * @inheritdoc
     */
    public function validate(array $row): bool
    {
        return
            isset($row[$this->priceIndex]) &&
            (float)$row[$this->priceIndex] <= $this->maxPrice;
    }
}