<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use BayAreaWebPro\SimpleCsv\SimpleCsv;
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
        $this->assertInstanceOf(SimpleCsv::class, $this->app->make('simple-csv'), 'Container can make instance of service.');
    }

    public function test_facade_can_resolve_instance()
    {
        $this->assertInstanceOf(SimpleCsv::class, \SimpleCsv::getFacadeRoot(), 'Facade can make instance of service.');
    }

    public function test_service_can_be_resolved()
    {
        $csv = app('simple-csv');
        $this->assertTrue(($csv instanceof SimpleCsv));
    }
}
