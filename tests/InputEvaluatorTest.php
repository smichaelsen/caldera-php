<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Test;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\InputEvaluator;

class InputEvaluatorTest extends TestCase
{
    /**
     * @test
     * @dataProvider dataProvider
     */
    public function getValueFromArray(string $valuePath, string $expectedValue)
    {
        $inputArray = [
            'name' => 'Bob Torrance',
            'born' => [
                'year' => '1888',
                'place' => 'Kirkintilloch, Scotland',
            ],
            'career' => [
                [
                    'team' => 'Kirkintilloch Rob Roy',
                ],
                [
                    'team' => 'Bradford City',
                ],
            ],
        ];
        $this->assertSame($expectedValue, InputEvaluator::getValueFromArray($inputArray, $valuePath));
    }

    /**
     * @test
     * @dataProvider dataProvider
     */
    public function getValueFromSimpleXMLElement(string $valuePath, string $expectedValue)
    {
        $xml = <<<XML
<?xml version="1.0" encoding="utf-8" ?>
<root>
    <name>Bob Torrance</name>
    <born>
        <year>1888</year>
        <place>Kirkintilloch, Scotland</place>    
    </born>
    <career>
        <careerItem>
            <team>Kirkintilloch Rob Roy</team>
        </careerItem>
        <careerItem>
            <team>Bradford City</team>
        </careerItem>
    </career>
</root>
XML;
        $xmlObject = new \SimpleXMLElement($xml);
        $this->assertSame($expectedValue, InputEvaluator::getValueFromSimpleXMLElement($xmlObject, $valuePath));
    }

    public function dataProvider()
    {
        return [
            ['name', 'Bob Torrance'],
            ['born.year', '1888'],
            ['born.place', 'Kirkintilloch, Scotland'],
            ['career.0.team', 'Kirkintilloch Rob Roy'],
            ['career.1.team', 'Bradford City'],
        ];
    }
}
