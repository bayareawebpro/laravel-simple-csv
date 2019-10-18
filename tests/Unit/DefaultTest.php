<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Support\LazyCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

use BayAreaWebPro\SimpleCsv\SimpleCsvFacade as SimpleCsv;
use BayAreaWebPro\SimpleCsv\Tests\TestCase;

class LazyGenerator
{
    /**
     * @param int $total
     * @param \Closure $callback
     * @return LazyCollection
     */
    public static function make(int $total, \Closure $callback): LazyCollection
    {
        $faker = app(\Faker\Generator::class);
        $count = 0;
        return LazyCollection::make(function () use (&$count, $total, $faker, $callback) {
            while ($count < $total) {
                $count++;
                yield $callback($faker);
            }
        });
    }
}

class DefaultTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        File::cleanDirectory(storage_path());
    }

    private function getCollectionData($total = 1000): LazyCollection
    {
        return LazyGenerator::make($total, function (\Faker\Generator $faker) {
            return [
                'uuid'  => (string)$faker->uuid,
                'name'  => (string)$faker->name,
                'email' => (string)$faker->email,
            ];
        });
    }

    private function getRandomStoragePath()
    {
        return storage_path(Str::random(16) . '.csv');
    }

    public function test_export_files_and_restore()
    {
        $items = $this->getCollectionData()->toArray();

        $collectionLazy = LazyCollection::make($items);

        $path = $this->getRandomStoragePath();

        SimpleCsv::export($collectionLazy, $path);

        $this->assertFileExists($path);

        $fileData = File::get($path);
        foreach ($items as $item) {
            $this->assertStringContainsString($item['email'], $fileData);
        }

        $decoded = SimpleCsv::import($path);
        $this->assertTrue($decoded instanceof LazyCollection);
        foreach ($decoded as $decodedItem) {
            $this->assertStringContainsString($decodedItem['email'], $fileData);
        }
    }

    public function test_can_download_streams()
    {
        $items = $this->getCollectionData()->toArray();

        $collectionLazy = LazyCollection::make($items);

        $response = SimpleCsv::download($collectionLazy, 'download.csv');

        $this->assertTrue($response instanceof StreamedResponse);

        //Capture Streamed Output...
        ob_start();
        $response->sendContent();
        $data = (string)ob_get_clean();
        //Capture Streamed Output...

        $this->assertNotEmpty($data);
        foreach ($items as $item) {
            $this->assertStringContainsString($item['email'], $data);
        }
    }
}
