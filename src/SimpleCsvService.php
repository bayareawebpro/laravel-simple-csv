<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv;

use BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull;
use BayAreaWebPro\SimpleCsv\Casts\NumericValues;
use Exception;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\{Arr, Facades\App, LazyCollection};
use SplFileObject;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SimpleCsvService
{
    protected array $headers = [];
    protected SplFileObject|null $file;

    public function __construct(
        #[Config('simple-csv.delimiter',',')] protected string $delimiter,
        #[Config('simple-csv.enclosure', '"')] protected string $enclosure,
        #[Config('simple-csv.escape', '\\')] protected string $escape,
        #[Config('simple-csv.casts', [
            EmptyValuesToNull::class,
            NumericValues::class
        ])] protected array $casts,
    )
    {
        //
    }

    public function import(string $path, array $casts = []): LazyCollection
    {
        $this->openFileObject($path);

        $this->headers = array_values($this->getLine());

        $casts = array_merge($this->casts, $casts);

        return LazyCollection::make(function () use ($casts) {
            while ($this->file->valid() && $line = $this->getLine()) {

                if ($this->isEmptyLine($line)) {
                    continue;
                }

                $item = array_combine($this->headers, $line);

                foreach($casts as $caster){
                    $item = App::call($caster, ['item' => $item]);
                }

                yield $item;
            }
            $this->resetState();
        });
    }

    protected function resetState(): void
    {
        $this->headers = [];
        $this->file = null;
    }

    protected function isEmptyLine(array $line): bool
    {
        return empty($line) || count($line) === 1 && is_null($line[0]);
    }

    public function export(iterable $collection, string $path): self
    {
        if (!file_exists($path)) touch($path);
        $this->openFileObject($path, 'w');
        $this->writeLines($collection);
        $this->resetState();
        return $this;
    }

    public function download(iterable $collection, string $filename, $headers = []): StreamedResponse
    {
        return response()->streamDownload(function () use ($collection) {
            $this->openFileObject('php://output', 'w');
            $this->writeLines($collection);
            $this->resetState();
        }, $filename, [
            'Content-Type' => 'text/csv',
            ...$headers
        ]);
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
        if($entry instanceof Arrayable){
            return $entry->toArray();
        }
        return (array)$entry;
    }

    protected function openFileObject(string $path, string $mode = 'r'): void
    {
        $this->file = new SplFileObject($path, $mode);
    }

    protected function writeLines(iterable $collection): void
    {
        foreach ($collection as $index => $row) {

            if(!Arr::isAssoc($row)){
                throw new Exception("Iterable Item (index: {$index}) is not associative array.");
            }

            if (!$this->headers) {
                $this->headers = array_keys($this->flattenRow($row));
                $this->writeLine($this->headers);
            }
            $this->writeLine(array_values($this->flattenRow($row)));
        }
    }
}
