<?php
namespace Smichaelsen\Caldera\Test\Validation;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\Validation\IsIso31661Alpha3CountryCodeValidator;

class IsIso31661Alpha3CountryCodeValidatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function validate($value, $expected)
    {
        $validator = new IsIso31661Alpha3CountryCodeValidator();
        $this->assertEquals($expected, $validator->validate($value));
    }

    public function dataProvider()
    {
        return [
            ['', true],
            ['DEU', true],
            ['deu', false],
            ['ATF', true],
            ['WTF', false],
            [true, false],
            [false, false],
            [[], false],
            [0, false],
            [12, false],
            [-1, false],
        ];
    }
}
