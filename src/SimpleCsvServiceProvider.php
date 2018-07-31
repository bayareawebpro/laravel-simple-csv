<?php namespace BayAreaWebPro\SimpleCsv;

/**
 * The SimpleCsv Service Provider
 */
class SimpleCsvServiceProvider extends \Illuminate\Support\ServiceProvider
{

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('simple-csv', function () {
            return $this->app->make(SimpleCsv::class);
        });
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
