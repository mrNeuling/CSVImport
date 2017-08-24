<?php

namespace Tests\ImportBundle\Validator;


use ImportBundle\Validator\MinPriceAndMinStockValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class MinPriceAndMinStockValidatorTest
 * @package Tests\ImportBundle\Validator
 */
class MinPriceAndMinStockValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            [true, ['10', '10'], 5, 5, 0, 1],
            [true, ['10', '2'], 5, 5, 0, 1],
            [true, ['1', '10'], 5, 5, 0, 1],
            [true, ['', '10'], 5, 5, 0, 1],
            [true, ['10', ''], 5, 5, 0, 1],

            [false, ['1', '2'], 5, 5, 0, 1],
            [false, ['1', '2'], 5, 5, 0, 3],
            [false, ['10', '10'], 5, 5, 0, 3],
            [false, ['1', '2'], 5, 5, 2, 1],
            [false, ['10', '10'], 5, 5, 2, 1],
            [false, ['', ''], 5, 5, 0, 1],
        ];
    }

    /**
     * @dataProvider getTestData
     * @param bool $expected
     * @param array $data
     * @param float $minPrice
     * @param int $minStock
     * @param int $priceIndex
     * @param int $stockIndex
     */
    public function testValidator(bool $expected, array $data, float $minPrice, int $minStock, int $priceIndex, int $stockIndex)
    {
        $validator = new MinPriceAndMinStockValidator($minPrice, $minStock, $priceIndex, $stockIndex);
        $this->assertEquals($expected, $validator->validate($data));
    }
}