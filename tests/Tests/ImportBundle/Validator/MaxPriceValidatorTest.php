<?php

namespace Tests\ImportBundle\Validator;


use ImportBundle\Validator\MaxPriceValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class MaxPriceValidatorTest
 * @package Tests\ImportBundle\Validator
 */
class MaxPriceValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestData(): array
    {
        $keyName = 'key';
        return [
            [true, ['150.25'], 200, 0],
            [true, [$keyName => '150.25'], 200, $keyName],
            [true, ['150'], 200, 0],
            [true, [$keyName => '150'], 200, $keyName],
            [true, ['150'], 150, 0],
            [true, [$keyName => '150'], 150, $keyName],
            [true, [''], 150, 0],
            [true, [$keyName => ''], 150, $keyName],

            [false, ['150.25'], 150, 0],
            [false, [$keyName => '150.25'], 150, $keyName],
            [false, ['150.25'], 200, 1],
            [false, [$keyName => '150.25'], 200, $keyName . '1'],
            [false, [], 150, 0],
            [false, [], 150, $keyName],
        ];
    }

    /**
     * @dataProvider getTestData
     * @param bool $expected
     * @param array $data
     * @param float $maxPrice
     * @param $index
     */
    public function testValidator(bool $expected, array $data, float $maxPrice, $index)
    {
        $validator = new MaxPriceValidator($maxPrice, $index);
        self::assertEquals($expected, $validator->validate($data));
    }
}