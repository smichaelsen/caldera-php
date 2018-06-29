<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\InputGenerator;

interface InputGeneratorInterface
{
    /**
     * @return \Generator
     */
    public function generateInput(): \Generator;
}
