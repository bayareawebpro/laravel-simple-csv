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

    /**
     * Importer constructor.
     * @param $collection \Illuminate\Support\Collection
     * @param $delimiter string
     * @param $enclosure string
     * @param $escape string
     */
    public function __construct(Collection $collection, $delimiter = ",", $enclosure = "\"", $escape = "\\")
    {
        $this->collection = $collection->all();
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
    }

    /**
     * Read Lines
     * @return iterable
     */
    public function generateLines(){
        $total = count($this->collection)-1;
        for($i = 0; $i <= $total;  $i++){
            yield $this->collection[$i];
        }
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
    private function loopLines($csv)
    {
        //Iterate the entries.
        foreach($this->generateLines() as $index => $entry){

            //Write the row headers.
            if ($index === 0) $this->writeLine($csv, array_keys($this->getRow($entry)));

            //Write the row entry.
            $this->writeLine($csv, array_values($this->getRow($entry)));
        }
    }

    /**
     * Write a line to the file.
     * @param $csv SplFileObject
     * @param $line array
     * @return void
     */
    private function writeLine($csv, $line)
    {
        $csv->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * Get The File Object
     * @param $path string
     * @return SplFileObject
     */
    private function getFileObject($path)
    {
        return new SplFileObject($path, "w");
    }

    /**
     * Touch the File.
     * @param $path string
     * @return void
     */
    private function touchFile($path)
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
    private function getRow($entry)
    {
        return method_exists($entry, 'getAttributes') ? $entry->getAttributes() : (array) $entry;
    }

}
