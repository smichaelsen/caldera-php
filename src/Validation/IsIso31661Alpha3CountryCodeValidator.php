<?php
namespace Smichaelsen\Caldera\Validation;

/**
 * Checks whether the input is a valid ISO 3166-1 ALPHA 3 country code. Empty string = true
 */
class IsIso31661Alpha3CountryCodeValidator implements ValidatorInterface
{
    public function validate($input): bool
    {
        $input = (string)$input;
        if ($input === '') {
            return true;
        }
        static $alpha3CountryCodes;
        if (!is_array($alpha3CountryCodes)) {
            $alpha3CountryCodes = array_keys(\CountryCodes::get('alpha3'));
        }
        return in_array($input, $alpha3CountryCodes);
    }
}
