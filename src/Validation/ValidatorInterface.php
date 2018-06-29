<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Validation;

interface ValidatorInterface
{
    public function validate($input): bool;
}
