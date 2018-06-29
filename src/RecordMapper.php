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
        foreach ($inputGenerator->generateInput() as $inputRecord) {
            try {
                $outputRecord = self::map($inputRecord, $mapping);
                yield $outputRecord;
            } catch (ValidationException $e) {
                if (!$skipInvalidRecords) {
                    throw $e;
                }
            }
        }
    }

    private static function map(array $inputRecord, MappingInterface $mapping): array
    {
        $mappingArray = $mapping->getMapping();
        $outputRecord = [];
        foreach ($mappingArray as $fieldName => $fieldConfiguration) {
            $value = self::getValue($inputRecord, $fieldConfiguration);
            if (!self::validate($value, $fieldConfiguration)) {
                throw new ValidationException('Validation error for field ' . $fieldName, 1530260719);
            }
            $outputRecord[$fieldName] = $value;
        }
        return $outputRecord;
    }

    private static function getValue(array $inputRecord, array $fieldConfiguration): string
    {
        $possibleColumnNames = array_map('trim', explode('//', $fieldConfiguration['column']));
        foreach ($possibleColumnNames as $possibleColumnName) {
            if (!isset($inputRecord[$possibleColumnName])) {
                continue;
            }
            $value = $inputRecord[$possibleColumnName];
            if (!empty($value)) {
                return $value;
            }
        }
        return '';
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
                throw new \Exception('Validators must be an existing class name or callabe', 1530261879);
            }
        }
        return true;
    }
}
