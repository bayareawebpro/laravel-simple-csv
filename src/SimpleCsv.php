<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv;

use SplFileObject;
use Illuminate\Support\LazyCollection;

class SimpleCsv
{
    const ESCAPE = '\\';
    const DELIMITER = ',';
    const ENCLOSURE = '"';

    /**
     * @var string
     */
    protected $delimiter, $enclosure, $escape;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var SplFileObject|null
     */
    protected $file;

    public function __construct()
    {
        $this->escape = static::ESCAPE;
        $this->delimiter = static::DELIMITER;
        $this->enclosure = static::ENCLOSURE;
        $this->headers = null;
        $this->file = null;
    }

    /**
     * Set Delimiter String
     * @param string $delimiter
     * @return $this
     */
    public function setDelimiter(string $delimiter): self
    {
        $this->delimiter = $delimiter;
        return $this;
    }

    /**
     * Set Enclosure String
     * @param string $enclosure
     * @return $this
     */
    public function setEnclosure(string $enclosure): self
    {
        $this->enclosure = $enclosure;
        return $this;
    }

    /**
     * Set Escape String
     * @param string $escape
     * @return $this
     */
    public function setEscape(string $escape): self
    {
        $this->escape = $escape;
        return $this;
    }

    /**
     * Import CSV to Lazy Collection
     * @param string $path
     * @return LazyCollection
     * @throws \Throwable
     */
    public function import(string $path): LazyCollection
    {
        $this->openFileObject($path);
        $this->headers = array_values($this->getLine());
        return LazyCollection::make(function () use ($path) {
            while ($this->file->valid() && $line = $this->getLine()) {
                if (!$this->isInValidLine($line)) {
                    yield array_combine($this->headers, $line);
                }
            }
        });
    }

    /**
     * Is InValid Line?
     * @param $line
     * @return bool
     */
    protected function isInValidLine($line): bool
    {
        return count($line) === 1 && is_null($line[0]);
    }

    /**
     * Export to FileSystem.
     * @param LazyCollection $collection
     * @param string $path
     * @return self
     * @throws \Throwable
     */
    public function export(LazyCollection $collection, string $path): self
    {
        //Get the file object.
        if (!file_exists($path)) touch($path);
        $this->openFileObject($path, 'w');
        $this->writeLines($collection);
        $this->closeFileObject();
        return $this;
    }

    /**
     * Download Response
     * @param LazyCollection $collection
     * @param $filename string
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Throwable
     */
    public function download(LazyCollection $collection, string $filename)
    {
        return response()->streamDownload(function () use ($collection) {
            $this->openFileObject('php://output', 'w');
            $this->writeLines($collection);
            $this->closeFileObject();
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    /**
     * Get Current Line
     * @return array
     */
    protected function getLine(): array
    {
        return $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * Write a line to the file.
     * @param $line array
     * @return void
     */
    protected function writeLine($line): void
    {
        $this->file->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    /**
     * Get The Row Data (from Model or Array)
     * @param $entry mixed
     * @return array
     */
    protected function flattenRow($entry): array
    {
        return method_exists($entry, 'toArray') ? $entry->toArray() : (array)$entry;
    }

    /**
     * Open The File Object
     * @param string $path
     * @param string $mode
     * @return void
     * @throws \Throwable
     */
    protected function openFileObject(string $path, $mode = 'r'): void
    {
        $this->file = new \SplFileObject($path, $mode);
    }

    /**
     * Close the file object.
     */
    protected function closeFileObject(): void
    {
        $this->file = null;
    }

    /**
     * @param LazyCollection $collection
     */
    protected function writeLines(LazyCollection $collection): void
    {
        $collection->chunk(500)->each(function (LazyCollection $chunk) {
            $chunk->each(function ($entry) {
                if (!$this->headers) {
                    $this->headers = array_keys($this->flattenRow($entry));
                    $this->writeLine($this->headers);
                }
                $this->writeLine(array_values($this->flattenRow($entry)));
                unset($entry);
            });
            unset($chunk);
        });
    }
}
