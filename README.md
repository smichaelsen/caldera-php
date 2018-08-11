# Caldera - PHP Data Import Tool

Caldera helps you to import data from sources like XML or CSV, to map, transform and validate them according to your needs and provides them with small memory footprint.

```
$csvInputGenerator = new \Smichaelsen\Caldera\InputGenerator\CsvInputGenerator();
$csvInputGenerator->setCsvHandle(fopen(__DIR__ . '/test-equestrian.csv', 'r'));

foreach (\Smichaelsen\Caldera\RecordMapper::mapRecords(
    $csvInputGenerator,
    new MyMappingClass()
) as $record) {
    // $record is mapped, validated and processed according to rules in your MyMappingClass() 
}
```

Caldera works line by line (record by record) when possible (not possible with XML) and this way saves memory.
This enables you to import data from a huge CSV file for example.

## Run tests:

```
composer update --prefer-lowest --prefer-stable
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
composer update
./vendor/bin/phpunit --bootstrap vendor/autoload.php tests
```
