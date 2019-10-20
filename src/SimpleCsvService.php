<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv;

use Iterator;
use Generator;
use SplFileObject;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;

class SimpleCsvService
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
        $this->escape = self::ESCAPE;
        $this->delimiter = self::DELIMITER;
        $this->enclosure = self::ENCLOSURE;
        $this->headers = null;
        $this->file = null;
    }

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

    protected function isInValidLine(array $line): bool
    {
        return count($line) === 1 && is_null($line[0]);
    }

    public function export($collection, string $path): self
    {
        if (!file_exists($path)) touch($path);
        $this->openFileObject($path, 'w');
        $this->writeLines($collection);
        $this->closeFileObject();
        return $this;
    }

    public function download($collection, string $filename)
    {
        return response()->streamDownload(function () use ($collection) {
            $this->openFileObject('php://output', 'w');
            $this->writeLines($collection);
            $this->closeFileObject();
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    protected function getLine(): array
    {
        return $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
    }

    protected function writeLine(array $line): void
    {
        $this->file->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    protected function flattenRow(array $entry): array
    {
        return method_exists($entry, 'toArray') ? $entry->toArray() : (array)$entry;
    }

    protected function openFileObject(string $path, string $mode = 'r'): void
    {
        $this->file = new \SplFileObject($path, $mode);
    }

    protected function closeFileObject(): void
    {
        $this->file = null;
    }

    protected function writeLines($collection): void
    {
        if(
            !$collection instanceof Iterator &&
            !$collection instanceof Generator &&
            !$collection instanceof Collection &&
            !$collection instanceof LazyCollection &&
            !is_array($collection)
        ){
            throw new \Exception("Non-Iterable Object cannot be iterated.");
        }
        foreach ($collection as $entry){
            if (!$this->headers) {
                $this->headers = array_keys($this->flattenRow($entry));
                $this->writeLine($this->headers);
            }
            $this->writeLine(array_values($this->flattenRow($entry)));
            unset($entry);
        }
    }
}