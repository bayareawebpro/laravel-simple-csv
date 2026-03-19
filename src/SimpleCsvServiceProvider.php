<?php

namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\ServiceProvider;

class SimpleCsvServiceProvider extends ServiceProvider
{

    /**
     * Register package services.
     */
    public function register(): void
    {
        $this->app->bind('simple-csv', SimpleCsvService::class);

        $this->publishes([
            __DIR__.'/../config/simple-csv.php' => config_path('simple-csv.php'),
        ], 'simple-csv');
    }

    /**
     * Bootstrap package services.
     */
    public function boot(): void
    {

    }

    /**
     * The services the package provides.
     */
    public function provides(): array
    {
        return ['simple-csv'];
    }
}
