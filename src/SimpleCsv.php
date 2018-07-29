<?php namespace BayAreaWebPro\SimpleCsv;
/**
 * The SimpleCsv Service Provider
 */
class SimpleCsv
{
    protected $csvImporter, $csvExporter;

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
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     * @return \Illuminate\Support\Collection
     */
    public function import($path = null, $delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        return with(new $this->csvImporter($delimiter, $enclosure, $escape))->import($path);
    }

    /**
     * Import the CSV to a new Collection
     * @param $collection \Illuminate\Support\Collection
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     * @return SimpleCsvExporter
     */
    public function export(\Illuminate\Support\Collection $collection, $delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        return with(new $this->csvExporter($collection, $delimiter, $enclosure, $escape));
    }
}
