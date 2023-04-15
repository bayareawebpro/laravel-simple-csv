<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull;
use BayAreaWebPro\SimpleCsv\Casts\NumericValues;
use BayAreaWebPro\SimpleCsv\LazyCsvCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Illuminate\Support\LazyCollection;
use Illuminate\Support\Collection;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use BayAreaWebPro\SimpleCsv\Tests\LazyGenerator;
use BayAreaWebPro\SimpleCsv\Tests\TestCase;
use BayAreaWebPro\SimpleCsv\SimpleCsv;

class DefaultTest extends TestCase
{
    private function getCollectionData($total = 1000): LazyCollection
    {
        return LazyGenerator::make($total, function (\Faker\Generator $faker) {
            return [
                'uuid'  => (string)$faker->uuid,
                'name'  => (string)$faker->name,
                'email' => (string)$faker->email,
                'empty' => null,
                'float' => 3.14,
                'int' => 256,
            ];
        });
    }

    private function getRandomStoragePath()
    {
        File::cleanDirectory(storage_path());
        return storage_path(Str::random(16) . '.csv');
    }

    public function test_import_casts()
    {
        $items = $this->getCollectionData(5)->toArray();

        $path = $this->getRandomStoragePath();

        SimpleCsv::export($items, $path);

        $this->assertFileExists($path);

        $items = SimpleCsv::import($path, [
            EmptyValuesToNull::class,
            NumericValues::class
        ]);

        foreach($items as $item){
            $this->assertIsFloat($item['float']);
            $this->assertNull($item['empty']);
            $this->assertIsInt($item['int']);
        }
    }

    public function test_export_from_iterables()
    {
        $items = $this->getCollectionData(10)->toArray();

        // Array
        $pathA = $this->getRandomStoragePath();
        SimpleCsv::export($items, $pathA);

        $this->assertFileExists($pathA);
        foreach ($items as $item) {
            $this->assertStringContainsString($item['uuid'], File::get($pathA));
        }

        // Collection
        $pathB = $this->getRandomStoragePath();
        SimpleCsv::export(Collection::make($items), $pathB);

        $this->assertFileExists($pathB);
        foreach ($items as $item) {
            $this->assertStringContainsString($item['uuid'], File::get($pathB));
        }
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
        $this->assertInstanceOf(LazyCollection::class, $decoded);

        foreach ($decoded as $decodedItem) {
            $this->assertStringContainsString($decodedItem['email'], $fileData);
        }
    }

    public function test_can_download_streams()
    {
        $items = $this->getCollectionData()->toArray();

        $collectionLazy = LazyCollection::make($items);

        $response = SimpleCsv::download($collectionLazy, 'download.csv');

        $this->assertInstanceOf(StreamedResponse::class, $response);

        ob_start();
        $response->sendContent();
        $data = (string)ob_get_clean();

        $this->assertNotEmpty($data);

        foreach ($items as $item) {
            $this->assertStringContainsString($item['email'], $data);
        }
    }
}
