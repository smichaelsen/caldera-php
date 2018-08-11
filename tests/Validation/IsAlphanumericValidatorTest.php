<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Test\Validation;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\Validation\IsAlphanumericValidator;

class IsAlphanumericValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     * @param mixed $value
     * @param bool $expected
     */
    public function validate($value, bool $expected)
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
