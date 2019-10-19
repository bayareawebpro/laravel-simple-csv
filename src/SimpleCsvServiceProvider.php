<?php

namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\ServiceProvider;

class SimpleCsvServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('simple-csv', SimpleCsv::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }

    /**
     * The services provided.
     *
     * @return array
     */
    public function provides()
    {
        return ['simple-csv'];
    }
}
