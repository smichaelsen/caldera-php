<?php
namespace Smichaelsen\Caldera\Test\Validation;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\Validation\IsNotEmptyValidator;

class IsNotEmptyValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     * @param mixed $value
     * @param bool $expected
     */
    public function validate($value, $expected)
    {
        $validator = new IsNotEmptyValidator();
        $this->assertEquals($expected, $validator->validate($value));
    }

    public function dataProvider()
    {
        return [
            ['', false],
            [true, true],
            [false, false],
            [' abc', true],
            ['abc', true],
            ['abc1', true],
            ['0', false],
            [0, false],
            [12, true],
        ];
    }
}
