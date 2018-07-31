<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Collection;
use SplFileObject;

/**
 * The SimpleCsv Importer
 */
class SimpleCsvImporter
{
    protected $delimiter, $enclosure, $escape;

    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const ESCAPE = '\\';
    /**
     * Importer constructor.
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     */
    public function __construct($delimiter = self::DELIMITER, $enclosure = self::ENCLOSURE, $escape = self::ESCAPE)
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * Get The File Object
     * @param $path string
     * @return iterable
     */
    private function generateLines($path){
        //Read the file contents as csv.
        $file = $this->getFileObject($path);
        while(($line = $file->fgetcsv($this->delimiter, $this->enclosure, $this->escape)) && $file->valid()) {
            yield $line;
        }
        //Close the file.
        $file = null;
    }

    /**
     * Import the CSV to a new Collection
     * @param $path string
     * @return Collection
     */
    public function import($path)
    {
        $headers = array();
        $lines = array();
        //Get the entries as a collection.
        foreach($this->generateLines($path) as $index => $line){
            if($index === 0){
                $headers = $line;
            }else{
                array_push($lines, array_combine($headers, $line));
            }
        }
        return new Collection($lines);
    }

    /**
     * Get The File Object
     * @param $path
     * @return SplFileObject
     */
    private function getFileObject($path)
    {
        return new SplFileObject($path, "r");
    }

}
