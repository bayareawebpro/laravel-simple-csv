<?php
namespace BayAreaWebPro\SimpleCsv\Tests;

use BayAreaWebPro\SimpleCsv\SimpleCsv;
use BayAreaWebPro\SimpleCsv\SimpleCsvServiceProvider;
use Faker\Generator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    protected function getPackageProviders($app): array
    {
        return [SimpleCsvServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'SimpleCsv' => SimpleCsv::class,
        ];
    }

    protected function getCollectionData($total = 1000): LazyCollection
    {
        return LazyGenerator::make($total, function (Generator $faker) {
            return [
                'uuid'  => (string)$faker->uuid,
                'name'  => (string)$faker->name,
                'email' => (string)$faker->email,
                'float' => '3.14',
                'empty' => null,
                'int' => '256',
            ];
        });
    }

    protected function getRandomStoragePath(): string
    {
        if(!File::isDirectory(storage_path('csv'))){
            File::makeDirectory(storage_path('csv'));
        }
        File::cleanDirectory(storage_path('csv'));

        return storage_path('csv/test-'.Str::random() . '.csv');
    }
}
