<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv;

use BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull;
use BayAreaWebPro\SimpleCsv\Casts\NumericValues;
use Illuminate\Container\Attributes\Config;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\{Collection, Facades\App, Facades\File, LazyCollection};
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

        $casts = $this->makeCasts($casts);

        return LazyCollection::make(function () use ($casts) {
            while ($this->file->valid() && $line = $this->getLine()) {

                if ($this->isEmptyLine($line)) {
                    continue;
                }

                $item = array_combine($this->headers, $line);

                foreach($casts as $cast){
                    $item = App::call($cast, ['item' => $item]);
                }

                yield $item;
            }
            $this->resetState();
        });
    }

    public function export(iterable $items, string $path): self
    {
        $this->openFileObject($path, 'w');
        $this->writeLines($items);
        $this->resetState();
        return $this;
    }

    public function download(iterable $items, string $fileName, $headers = []): StreamedResponse
    {
        return response()->streamDownload(function () use ($items) {
            $this->openFileObject('php://output', 'w');
            $this->writeLines($items);
            $this->resetState();
        }, $fileName, [
            'Content-Type' => 'text/csv',
            ...$headers
        ]);
    }

    protected function openFileObject(string $path, string $mode = 'r'): void
    {
        $this->file = new SplFileObject($path, $mode);
    }

    protected function getLine(): array
    {
        return $this->file->fgetcsv($this->delimiter, $this->enclosure, $this->escape);
    }

    protected function writeLine(array $line): void
    {
        $this->file->fputcsv($line, $this->delimiter, $this->enclosure, $this->escape);
    }

    protected function writeLines(iterable $collection): void
    {
        foreach ($collection as $row) {
            if (!$this->headers) {
                $this->headers = array_keys($this->flattenRow($row));
                $this->writeLine($this->headers);
            }
            $this->writeLine(array_values($this->flattenRow($row)));
        }
    }

    protected function flattenRow($entry): array
    {
        if($entry instanceof Arrayable){
            return $entry->toArray();
        }
        return (array)$entry;
    }

    protected function isEmptyLine(array $line): bool
    {
        return empty($line) || count($line) === 1 && is_null($line[0]);
    }

    protected function resetState(): void
    {
        $this->headers = [];
        $this->file = null;
    }

    protected function makeCasts(array $casts): Collection
    {
        return Collection::make($this->casts)
            ->merge($casts)
            ->unique()
            ->map(fn($cast) => App::make($cast));
    }

    protected function cleanUpFile(): void
    {
        if(!$this->file || !File::exists($this->file->getRealPath())){
            return;
        }

        File::delete($this->file->getRealPath());
    }
}
