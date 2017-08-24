<?php

namespace Tests\ImportBundle\Validator;


use ImportBundle\Validator\NumericValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class NumericValidatorTest
 * @package Tests\ImportBundle\Validator
 */
class NumericValidatorTest extends TestCase
{
    /**
     * @return array
     */
    public function getTestData(): array
    {
        return [
            [true, ['1'], 0],
            [false, ['1'], 1],
            [true, ['1.0'], 0],
            [false, ['1.0'], 1],
            [false, [], 0],
            [false, [''], 0],
            [true, ['1.'], 0],
            [true, ['', '399.99'], 1],
        ];
    }

    /**
     * @dataProvider getTestData
     * @param bool $expected
     * @param array $data
     * @param int $index
     */
    public function testValidator(bool $expected, array $data, int $index)
    {
        $validator = new NumericValidator($index);
        $this->assertEquals($expected, $validator->validate($data));
    }
}