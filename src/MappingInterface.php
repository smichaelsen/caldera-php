<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera;

interface MappingInterface
{
    public function firstLineContainsFieldNames(): bool;
    public function getMapping(): array;
}
