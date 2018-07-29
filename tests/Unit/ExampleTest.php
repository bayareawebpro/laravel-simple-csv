<?php

namespace Tests\Unit;

use BayAreaWebPro\SimpleCsv\SimpleCsv;
use Illuminate\Support\Collection;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Tests\TestCase;

class ExampleTest extends TestCase
{

    private function getCollectionData(){
        return factory(\App\User::class, 1000)->make();
    }
    private function getRandomStoragePath(){
        return storage_path(str_random(16) . '.csv');
    }
    private function getExpectedStoragePath(){
        return storage_path('simple-csv-test.csv');
    }

    /**
     * Test CSV can be resolved.
     *
     * @return void
     */
    public function test_csv_can_be_resolved()
    {
        $csv = app()->make('simple-csv');
        $this->assertInstanceOf(SimpleCsv::class, $csv);
    }

    /**
     * Test CSV can export files.
     *
     * @return void
     */
    public function test_csv_can_export_files()
    {
        $collection = $this->getCollectionData();
        $path = $this->getRandomStoragePath();

        $csv = app()->make('simple-csv');
        $csv->export($collection)->save($path);

        $this->assertTrue(app()->make('files')->exists($path));
    }

    /**
     * Test CSV can export download streams.
     *
     * @return void
     */
    public function test_csv_can_export_download_streams()
    {
        $collection = $this->getCollectionData();

        $csv = app()->make('simple-csv');
        $response = $csv->export($collection)->download('download.csv');

        $this->assertInstanceOf(StreamedResponse::class, $response);
    }
    /**
     * Test CSV can import files to collections.
     *
     * @return void
     */
    public function test_csv_can_import_files_and_restore_collections()
    {
        $collection = $this->getCollectionData();
        $path = $this->getExpectedStoragePath();

        //Will Fail if Prior Tests Fail.
        $csv = app()->make('simple-csv');
        $csv->export($collection)->save($path);

        $decoded = $csv->import($path);

        $this->assertInstanceOf(Collection::class, $decoded);

        $this->assertArraySubset($collection->toArray(), $decoded->toArray());
    }
}
