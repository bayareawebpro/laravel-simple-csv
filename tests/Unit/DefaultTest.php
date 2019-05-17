<?php

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use Illuminate\Support\Collection;
use BayAreaWebPro\SimpleCsv\SimpleCsv;
use BayAreaWebPro\SimpleCsv\Tests\TestCase;
use Symfony\Component\HttpFoundation\StreamedResponse;

class DefaultTest extends TestCase
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
     * @return void
     */
    public function test_csv_can_be_resolved()
    {
        $csv = app()->make('simple-csv');
        $this->assertTrue(($csv instanceof SimpleCsv));
    }

    /**
     * Test CSV can export files.
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
     * @return void
     */
    public function test_csv_can_export_download_streams()
    {
        $collection = $this->getCollectionData();

        $csv = app()->make('simple-csv');
        $response = $csv->export($collection)->download('download.csv');

        $this->assertTrue($response  instanceof StreamedResponse);
    }

    /**
     * Test CSV can import files to collections.
     * @return void
     */
    public function test_csv_can_import_files_and_restore_collections()
    {
        $collection = $this->getCollectionData();
        $path = $this->getExpectedStoragePath();

        //Will Fail if Prior Tests Fail.
        $csv = app()->make('simple-csv');
        $csv->export($collection)->save($path);

        /** @var  $decoded Collection */
        $decoded = $csv->import($path);

        $this->assertTrue($decoded instanceof Collection);

        $this->assertArraySubset($collection->toArray(), $decoded->toArray());
    }


	/**
	 * Test CSV can import files to collections.
	 * @return void
	 */
	public function test_csv_can_import_using_callback_inside_generator()
	{
		$rows = $this->getCollectionData();
		$path = $this->getExpectedStoragePath();

		//Will Fail if Prior Tests Fail.
		$csv = app()->make('simple-csv');
		$csv->export($rows)->save($path);

		$total = collect();
		$csv->import($path, function ($collection) use ($total) {
			/** @var  $collection Collection */
			$collection->each(function($row) use ($total){
				$total->push($row);
			});
		}, 100);

		$this->assertArraySubset($total->toArray(), $rows->toArray());
	}
}
