<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Facades\Facade as LaravelFacade;
use Illuminate\Support\LazyCollection;
/**
 * The SimpleCsv Service Facade
 * @method static \Symfony\Component\HttpFoundation\StreamedResponse download(LazyCollection $collection, string $filename)
 * @method static void export(LazyCollection $collection, string $path)
 * @method static LazyCollection import(string $path)
 */
class SimpleCsvFacade extends LaravelFacade
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
