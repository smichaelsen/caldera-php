<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera;

use Smichaelsen\Caldera\InputGenerator\InputGeneratorInterface;
use Smichaelsen\Caldera\Validation\ValidationException;
use Smichaelsen\Caldera\Validation\ValidatorInterface;

class RecordMapper
{
    public static function mapRecords(InputGeneratorInterface $inputGenerator, MappingInterface $mapping, bool $skipInvalidRecords = true): \Generator
    {
        $mappingArray = $mapping->getMapping();
        foreach ($inputGenerator->generateInput() as $inputRecord) {
            try {
                yield self::map($inputRecord, $mappingArray);
            } catch (ValidationException $e) {
                if ($skipInvalidRecords) {
                    continue;
                }
                throw $e;
            }
        }
    }

    private static function map($inputRecord, array $mappingArray): array
    {
        $outputRecord = [];
        foreach ($mappingArray as $fieldName => $fieldConfiguration) {
            $value = self::getValue($inputRecord, $fieldConfiguration);
            if (!self::validate($value, $fieldConfiguration)) {
                throw new ValidationException('Validation error for field ' . $fieldName, 1530260719);
            }
            $value = self::transform($value, $fieldConfiguration);
            $outputRecord[$fieldName] = $value;
        }
        return $outputRecord;
    }

    private static function getValue($inputRecord, array $fieldConfiguration)
    {
        if (isset($fieldConfiguration['value'])) {
            return $fieldConfiguration['value'];
        }
        if (isset($fieldConfiguration['column'])) {
            if (is_array($fieldConfiguration['column'])) {
                $possibleColumnNames = $fieldConfiguration['column'];
            } else {
                $possibleColumnNames = [$fieldConfiguration['column']];
            }
            foreach ($possibleColumnNames as $possibleColumnName) {
                $value = self::readValueFromColumn($inputRecord, $possibleColumnName);
                if (!empty($value)) {
                    return (string)$value;
                }
            }
        }
        if (isset($fieldConfiguration['reduce'])) {
            $dataForReduceFunction = [];
            foreach ($fieldConfiguration['reduce']['columns'] as $column) {
                $dataForReduceFunction[$column] = self::readValueFromColumn($inputRecord, $column);
            }
            return call_user_func($fieldConfiguration['reduce']['function'], $dataForReduceFunction);
        }
        return null;
    }

    private static function readValueFromColumn($inputRecord, string $columnName): ?string
    {
        if (is_array($inputRecord)) {
            $value = InputEvaluator::getValueFromArray($inputRecord, $columnName);
        } elseif (get_class($inputRecord) === \SimpleXMLElement::class) {
            $value = InputEvaluator::getValueFromSimpleXMLElement($inputRecord, $columnName);
        } else {
            throw new \Exception('Unsupported input record type. array or SimpleXMLElement are supported', 1559648232);
        }
        return $value;
    }

    private static function validate($value, array $fieldConfiguration): bool
    {
        if (!isset($fieldConfiguration['validation'])) {
            return true;
        }
        foreach ($fieldConfiguration['validation'] as $validator) {
            if (is_string($validator) && class_exists($validator)) {
                /** @var ValidatorInterface $validatorInstance */
                $validatorInstance = new $validator;
                if (!$validatorInstance instanceof ValidatorInterface) {
                    throw new \Exception($validator . ' does not implement the ValidatorInterface', 1530261769);
                }
                if (!$validatorInstance->validate($value)) {
                    return false;
                }
            } elseif (is_callable($validator)) {
                if (!$validator($value)) {
                    return false;
                }
            } else {
                throw new \Exception('Validators must be an existing class name or callable', 1530261879);
            }
        }
        return true;
    }

    private static function transform($value, array $fieldConfiguration)
    {
        if (!isset($fieldConfiguration['transforms']) || !is_array($fieldConfiguration['transforms'])) {
            return $value;
        }
        foreach ($fieldConfiguration['transforms'] as $transform) {
            $value = call_user_func($transform, $value);
        }
        return $value;
    }
}
