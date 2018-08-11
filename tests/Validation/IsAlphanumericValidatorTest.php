<?php
namespace Smichaelsen\Caldera\Test\Validation;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\Validation\IsAlphanumericValidator;

class IsAlphanumericValidatorTest extends TestCase
{

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function validate($value, $expected)
    {
        $validator = new IsAlphanumericValidator();
        $this->assertEquals($expected, $validator->validate($value));
    }

    public function dataProvider()
    {
        return [
            ['', true],
            [true, false],
            [false, false],
            [' abc', false],
            ['abc', true],
            ['abc1', true],
            ['0', true],
            [0, true],
            [12, true],
        ];
    }

}
