<?php

namespace ImportBundle\Validator;


/**
 * Class NumericValidator
 * @package ImportBundle\Validator
 */
class NumericValidator implements Validator
{
    /**
     * @var int
     */
    private $index;

    /**
     * IsNumericValidator constructor.
     * @param int $index
     */
    public function __construct(int $index)
    {
        $this->index = $index;
    }

    /**
     * @inheritdoc
     */
    public function validate(array $row): bool
    {
        return
            isset($row[$this->index]) &&
            preg_match('/^\d+\.?\d*$/', $row[$this->index]);
    }
}