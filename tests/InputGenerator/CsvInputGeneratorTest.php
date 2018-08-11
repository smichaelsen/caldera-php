<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Test\InputGenerator;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\InputGenerator\CsvInputGenerator;

class CsvInputGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function generatesCsvDataWithFieldNamesFromFirstLine()
    {
        $csvInputGenerator = new CsvInputGenerator();
        $csvInputGenerator->setCsvHandle(fopen(__DIR__ . '/test-equestrian.csv', 'r'));
        $csvInputGenerator->setSkipLeadingLines(2);
        $generatedArray = [];
        foreach ($csvInputGenerator->generateInput() as $inputRecord) {
            $generatedArray[] = $inputRecord;
        }
        $expectedArray = [
            [
                'Athlete' => 'Gemna Foo',
                'Horse' => 'Avalon',
                'Event' => 'Individual Championship Ia',
                'Score' => '65050',
                'Rank' => '13',
            ],
            [
                'Athlete' => 'Gemna Foo',
                'Horse' => 'Avalon',
                'Event' => 'Individual Freestyle Ia',
                'Score' => '65800',
                'Rank' => '12',
            ],
            [
                'Athlete' => 'Laurentia Tan',
                'Horse' => 'Ruben James 2',
                'Event' => 'Individual Championship Ia',
                'Score' => '73650',
                'Rank' => '3',
            ],
            [
                'Athlete' => 'Laurentia Tan',
                'Horse' => 'Ruben James 2',
                'Event' => 'Individual Freestyle Ia',
                'Score' => '79000',
                'Rank' => '2',
            ],
            [
                'Athlete' => 'Maximillian Tan',
                'Horse' => 'Avalon',
                'Event' => 'Individual Championship Ia',
                'Score' => '59304',
                'Rank' => '15',
            ],
            [
                'Athlete' => 'Maximillian Tan',
                'Horse' => 'Avalon',
                'Event' => 'Individual Freestyle Ia',
                'Score' => '62000',
                'Rank' => '14',
            ],
        ];
        $this->assertEquals($expectedArray, $generatedArray);
    }
}
