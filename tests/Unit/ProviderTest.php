<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use BayAreaWebPro\SimpleCsv\SimpleCsvService;
use BayAreaWebPro\SimpleCsv\SimpleCsvServiceProvider;
use BayAreaWebPro\SimpleCsv\Tests\TestCase;

class ProviderTest extends TestCase
{
    public function test_provider_is_registered()
    {
        $this->assertInstanceOf(SimpleCsvServiceProvider::class, $this->app->getProvider(SimpleCsvServiceProvider::class), 'Provider is registered with container.');
    }

    public function test_container_can_resolve_instance()
    {
        $this->assertInstanceOf(SimpleCsvService::class, $this->app->make('simple-csv'), 'Container can make instance of service.');
    }

    public function test_facade_can_resolve_instance()
    {
        $this->assertInstanceOf(SimpleCsvService::class, \SimpleCsv::getFacadeRoot(), 'Facade can make instance of service.');
    }

    public function test_service_can_be_resolved()
    {
        $csv = app('simple-csv');
        $this->assertInstanceOf(SimpleCsvService::class, $csv);
    }

    public function test_declares_provided()
    {
        $this->assertTrue(in_array('simple-csv',
            collect(app()->getProviders(SimpleCsvServiceProvider::class))->first()->provides())
        );
    }
}
