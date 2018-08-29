<?php

namespace Smichaelsen\Caldera\Test;

use PHPUnit\Framework\TestCase;
use Smichaelsen\Caldera\InputGenerator\InputGeneratorInterface;
use Smichaelsen\Caldera\MappingInterface;
use Smichaelsen\Caldera\RecordMapper;

class RecordMapperTest extends TestCase
{
    /**
     * @test
     */
    public function throwsExceptionOnInvalidInputRecord()
    {
        $this->expectExceptionCode(1535566812);
        $inputGenerator = $this->createMock(InputGeneratorInterface::class);
        $inputGenerator->method('generateInput')->willReturnCallback(function () {
            $yields = [
                ['title' => 'this is a record'],
                false, // this should raise an exception
            ];
            foreach ($yields as $yield) {
                yield $yield;
            }
        });
        $mapper = $this->createMock(MappingInterface::class);
        $mapper->method('getMapping')->willReturn(['title' => ['column' => 'title']]);
        foreach (RecordMapper::mapRecords($inputGenerator, $mapper) as $_) {
        }
    }
}
