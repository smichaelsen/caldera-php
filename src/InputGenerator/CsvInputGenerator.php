<?php
declare(strict_types = 1);
namespace Smichaelsen\Caldera\InputGenerator;

class CsvInputGenerator implements InputGeneratorInterface
{
    /**
     * @var array
     */
    protected $columnNames;

    protected $csvHandle;

    protected $csvDelimiter = ',';

    protected $firstRowContainsColumnNames = true;

    protected $skipLeadingLines = 0;

    public function setColumnNames(array $columnNames)
    {
        $this->columnNames = $columnNames;
    }

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
            throw new \Exception('Call ->setCsvHandle() before calling ->generateInput()', 1530262326);
        }
        $rowCount = 0;
        while (($inputData = fgetcsv($this->csvHandle, 0, $this->csvDelimiter)) !== false) {
            $rowCount++;
            if ($this->firstRowContainsColumnNames && $rowCount === 1) {
                $this->columnNames = $inputData;
                continue;
            }
            if ($rowCount <= $this->skipLeadingLines) {
                continue;
            }
            if (is_array($this->columnNames)) {
                $inputData = array_combine($this->columnNames, $inputData);
            }
            yield $inputData;
        }
        fclose($this->csvHandle);
    }
}
