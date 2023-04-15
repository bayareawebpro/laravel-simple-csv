<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Facades\App;
use \Iterator;
use \Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SimpleCsvService
{
    const DELIMITER = ',';
    const ENCLOSURE = '"';
    const ESCAPE = '\\';

    protected $delimiter, $enclosure, $escape;
    protected $headers;
    protected $file;

    public function __construct(
        string $delimiter = self::DELIMITER,
        string $enclosure = self::ENCLOSURE,
        string $escape = self::ESCAPE
    )
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
        $this->escape = $escape;
        $this->headers = null;
        $this->file = null;
    }

    public function import(string $path, array $casts = []): LazyCollection
    {
        $this->openFileObject($path);
        $this->headers = array_values($this->getLine());

        $instance = LazyCollection::make(function () {
            while ($this->file->valid() && $line = $this->getLine()) {
                if (!$this->isInValidLine($line)) {
                    yield array_combine($this->headers, $line);
                }
            }
            $this->resetState();
        });

        if(count($casts)){
            foreach($casts as $caster){
                $instance = $instance->map(App::make($caster));
            }
        }

        return $instance;
    }

    protected function resetState(): void
    {
        $this->headers = null;
        $this->file = null;
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
        $this->resetState();
        return $this;
    }

    public function download($collection, string $filename, $headers = []): StreamedResponse
    {
        return response()->streamDownload(function () use ($collection) {
            $this->openFileObject('php://output', 'w');
            $this->writeLines($collection);
            $this->resetState();
        }, $filename, array_merge([
            'Content-Type' => 'text/csv',
        ], $headers));
    }

    protected function getLine(): array
    {
        return $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
    }

    protected function writeLine(array $line): void
    {
        $this->file->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    protected function flattenRow($entry): array
    {
        return is_object($entry) && method_exists($entry, 'toArray') ? $entry->toArray() : (array)$entry;
    }

    protected function openFileObject(string $path, string $mode = 'r'): void
    {
        $this->file = new \SplFileObject($path, $mode);
    }

    protected function writeLines($collection): void
    {
        if (
            !$collection instanceof Iterator &&
            !$collection instanceof Collection &&
            !$collection instanceof LazyCollection &&
            !is_array($collection)
        ) {
            throw new Exception("Non-Iterable Object cannot be iterated.");
        }
        foreach ($collection as $entry) {
            if (!$this->headers) {
                $this->headers = array_keys($this->flattenRow($entry));
                $this->writeLine($this->headers);
            }
            $this->writeLine(array_values($this->flattenRow($entry)));
        }
    }
}
