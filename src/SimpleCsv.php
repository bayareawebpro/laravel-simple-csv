<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Facades\Facade as LaravelFacade;
use Iterator;

/**
 * The SimpleCsv Service Facade
 * @method static \Symfony\Component\HttpFoundation\StreamedResponse download(Collection|LazyCollection|Iterator|array $collection, string $filename)
 * @method static void export(Collection|LazyCollection|Iterator|array $collection, string $path)
 * @method static LazyCollection import(string $path, array $casts = [])
 */
class SimpleCsv extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simple-csv';
    }
}
