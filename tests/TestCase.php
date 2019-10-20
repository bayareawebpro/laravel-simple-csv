<?php
namespace BayAreaWebPro\SimpleCsv\Tests;

use BayAreaWebPro\SimpleCsv\SimpleCsv;
use BayAreaWebPro\SimpleCsv\SimpleCsvServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Load package service provider
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [SimpleCsvServiceProvider::class];
    }

    /**
     * Load package alias
     * @param \Illuminate\Foundation\Application $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'SimpleCsv' => SimpleCsv::class,
        ];
    }
}
