<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Test\Validation;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\Validation\IsNumericValidator;

class IsNumericValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     * @param mixed $value
     * @param bool $expected
     */
    public function validate($value, bool $expected)
    {
        $validator = new IsNumericValidator();
        $this->assertEquals($expected, $validator->validate($value));
    }

    public function dataProvider()
    {
        return [
            ['', true],
            [true, false],
            [false, false],
            [' abc', false],
            ['abc', false],
            ['abc1', false],
            ['0', true],
            ['-3', true],
            [0, true],
            [12, true],
        ];
    }
}
