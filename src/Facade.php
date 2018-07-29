<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Facades\Facade as LaravelFacade;

/**
 * The SimpleCsv Service Facade
 */
class Facade extends LaravelFacade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'simple-csv';
    }
}
