<?php namespace BayAreaWebPro\SimpleCsv;
/**
 * The SimpleCsv Facade
 */
class SimpleCsv
{
    protected $csvImporter, $csvExporter;

    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const ESCAPE = '\\';

    /**
     * SimpleCsv constructor.
     * @param $csvImporter SimpleCsvImporter
     * @param $csvExporter SimpleCsvExporter
     */
    public function __construct(SimpleCsvImporter $csvImporter, SimpleCsvExporter $csvExporter)
    {
        $this->csvImporter = $csvImporter;
        $this->csvExporter = $csvExporter;
    }

    /**
     * Import the CSV to a new Collection
     * @param $path string
     * @param $callback callable
     * @param $chunk int
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     * @return \Illuminate\Support\Collection
     */
    public function import(
    	$path = null,
	    $callback = null,
	    $chunk = 500,
	    $delimiter = self::DELIMITER,
	    $enclosure = self::ENCLOSURE,
	    $escape = self::ESCAPE
    ){
        return with(new $this->csvImporter($delimiter, $enclosure, $escape))->import($path, $callback, $chunk);
    }

    /**
     * Import the CSV to a new Collection
     * @param $collection \Illuminate\Support\Collection
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     * @return SimpleCsvExporter
     */
    public function export($collection, $delimiter = self::DELIMITER, $enclosure = self::ENCLOSURE, $escape = self::ESCAPE)
    {
        return with(new $this->csvExporter($collection, $delimiter, $enclosure, $escape));
    }
}
