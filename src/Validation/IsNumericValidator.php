<?php
namespace Smichaelsen\Caldera\Validation;

/**
 * Checks if the input is numeric or a numeric string. True for empty string.
 */
class IsNumericValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        if (is_bool($input)) {
            return false;
        }
        return (string)$input === '' || is_numeric($input);
    }
}
