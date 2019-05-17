<?php

namespace BayAreaWebPro\SimpleCsv\Tests;
use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require '/Users/builder/GitHub/laravel-simple-csv-testing/bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
