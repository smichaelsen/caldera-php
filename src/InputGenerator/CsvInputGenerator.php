<?php
declare(strict_types=1);
namespace Smichaelsen\Caldera\InputGenerator;

class CsvInputGenerator implements InputGeneratorInterface
{
    protected $csvHandle;

    protected $csvDelimiter = ',';

    protected $firstRowContainsColumnNames = true;

    protected $skipLeadingLines = 0;

    public function setCsvHandle($csvHandle)
    {
        $this->csvHandle = $csvHandle;
    }

    public function setCsvDelimiter(string $csvDelimiter)
    {
        $this->csvDelimiter = $csvDelimiter;
    }

    public function setFirstRowContainsColumnNames(bool $firstRowContainsColumnNames)
    {
        $this->firstRowContainsColumnNames = $firstRowContainsColumnNames;
    }

    public function setSkipLeadingLines(int $skipLeadingLines)
    {
        $this->skipLeadingLines = $skipLeadingLines;
    }

    public function generateInput(): \Generator
    {
        if (!is_resource($this->csvHandle)) {
            throw new \Exception('Call ->setCsvContent() before calling ->generateInput()', 1530262326);
        }
        $columnNames = null;
        $rowCount = 0;
        while (($inputData = fgetcsv($this->csvHandle, 0, $this->csvDelimiter)) !== false) {
            $rowCount++;
            if ($rowCount <= $this->skipLeadingLines && !($this->firstRowContainsColumnNames && $rowCount === 1)) {
                continue;
            }
            if ($this->firstRowContainsColumnNames) {
                if ($rowCount === 1) {
                    $columnNames = $inputData;
                    continue;
                }
                $inputData = array_combine($columnNames, $inputData);
            }
            yield $inputData;
        }
        fclose($this->csvHandle);
    }
}
