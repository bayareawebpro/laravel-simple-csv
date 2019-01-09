<?php namespace BayAreaWebPro\SimpleCsv;
use SplFileObject;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
/**
 * The SimpleCsv Exporter
 */
class SimpleCsvExporter
{
    protected $collection, $delimiter, $enclosure, $escape;

    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const ESCAPE = '\\';
    /**
     * Importer constructor.
     * @param $collection \Illuminate\Support\Collection
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     */
    public function __construct(Collection $collection, $delimiter = self::DELIMITER, $enclosure = self::ENCLOSURE, $escape = self::ESCAPE)
    {
        $this->collection = $collection;
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * Save the CSV File to Disk
     * @param $path string
     * @return void
     */
    public function save($path = 'export.csv')
    {
        $this->touchFile($path);

        //Get the file object.
        $csv = $this->getFileObject($path);

        $this->loopLines($csv);

        //Close the file.
        $csv = null;
    }

    /**
     * Export CSV File to Download Response
     * @param $filename string
     * @return StreamedResponse
     */
    public function download($filename = 'export.csv')
    {
        //Get the file object.
        $csv = $this->getFileObject('php://output');

        return new StreamedResponse(function () use ($csv) {

            $this->loopLines($csv);

        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Loop the lines of the file.
     * @param $csv SplFileObject
     * @return void
     */
    protected function loopLines($csv)
    {
        $generator = $this->collection->getIterator();

        //Write Headers
        $this->writeLine($csv, array_keys($this->getRow($generator->current())));

        //Write Rows
        while($generator->valid()){
            $this->writeLine($csv, array_values($this->getRow($generator->current())));
            $generator->next();
        }
    }

    /**
     * Write a line to the file.
     * @param $csv SplFileObject
     * @param $line array
     * @return void
     */
    protected function writeLine($csv, $line)
    {
        $csv->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * Get The File Object
     * @param $path string
     * @return SplFileObject
     */
    protected function getFileObject($path)
    {
        return new SplFileObject($path, "w");
    }

    /**
     * Touch the File.
     * @param $path string
     * @return void
     */
    protected function touchFile($path)
    {
        if (!file_exists($path)) {
            touch($path);
        }
    }

    /**
     * Get The Row Data (from Model or Array)
     * @param $entry mixed
     * @return array
     */
    protected function getRow($entry)
    {
        return method_exists($entry, 'toArray') ? $entry->toArray() : (array) $entry;
    }

}
