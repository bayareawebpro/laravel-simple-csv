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
	const ESCAPE = "\\";

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
	 * @return \Iterator
	 */
	protected function generateLines($path){
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
	 * @param $callback callable
	 * @param $chunk int
	 * @return Collection|null
	 */
	public function import($path, $callback = null, $chunk = 500)
	{
		$lines = array();
		$shouldCallback = is_callable($callback) && $chunk > 0;
		$generator = $this->generateLines($path);

		//Pluck the headers.
		$headers = $generator->current();

		//Move to first row.
		$generator->next();

		while($generator->valid()){
			array_push($lines, array_combine($headers, $generator->current()));

			if($shouldCallback && (count($lines) === $chunk)) {
				$lines = $this->handleCallback($callback, $lines);
			}
			$generator->next();
		}
		if($shouldCallback){
			$this->handleCallback($callback, $lines);
			return null;
		}
		return new Collection($lines);
	}

	/**
	 * Handle the chunk.
	 * @param $callback callable
	 * @param $lines array
	 * @return array
	 */
	protected function handleCallback($callback, $lines)
	{
		$callback(new Collection($lines));
		return array();
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
