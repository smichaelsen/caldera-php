<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\Validation;

/**
 * Checks if the input only contains numbers and letters. True for empty string.
 */
class IsAlphanumericValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        if (is_bool($input)) {
            return false;
        }
        return (string)$input === '' || ctype_alnum((string)$input);
    }
}
