<?php
namespace Smichaelsen\Caldera\Validation;

/**
 * Checks if the input is numeric or a numeric string. True for empty string.
 */
class IsNumericValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        return (string)$input === '' || is_numeric($input);
    }
}
