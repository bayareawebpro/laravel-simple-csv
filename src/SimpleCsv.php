<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Facade as LaravelFacade;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * The SimpleCsv Service Facade
 * @method static StreamedResponse download(iterable $items, string $fileName, array $headers = [])
 * @method static void export(iterable $items, string $path)
 * @method static LazyCollection import(string $path, array $casts = [])
 */
class SimpleCsv extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'simple-csv';
    }
}
