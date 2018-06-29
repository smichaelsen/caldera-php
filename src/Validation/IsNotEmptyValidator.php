<?php
namespace Smichaelsen\Caldera\Validation;

class IsNotEmptyValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        return !empty($input);
    }
}
