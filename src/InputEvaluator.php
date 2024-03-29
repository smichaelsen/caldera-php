<?php
namespace Smichaelsen\Caldera;

final class InputEvaluator
{
    public static function getValueFromArray(array $inputRecord, string $valuePath): ?string
    {
        if (isset($inputRecord[$valuePath])) {
            return (string)$inputRecord[$valuePath];
        }
        $value = $inputRecord;
        $currentValuePathPart = strtok($valuePath, '.');
        while ($currentValuePathPart !== false) {
            if (!isset($value[$currentValuePathPart])) {
                return null;
            }
            $value = $value[$currentValuePathPart];
            $currentValuePathPart = strtok('.');
        }
        return (string)$value;
    }

    public static function getValueFromSimpleXMLElement(\SimpleXMLElement $inputRecord, string $valuePath)
    {
        $value = $inputRecord;
        $currentValuePathPart = strtok($valuePath, '.');
        while ($currentValuePathPart !== false) {
            if (is_numeric($currentValuePathPart)) {
                $value = $value->xpath('*[' . ($currentValuePathPart + 1) . ']')[0];
            } elseif (isset($value->xpath($currentValuePathPart)[0])) {
                $value = $value->xpath($currentValuePathPart)[0];
            }
            if (empty((string)$value)) {
                return null;
            }
            $currentValuePathPart = strtok('.');
        }
        return (string)$value;
    }
}
