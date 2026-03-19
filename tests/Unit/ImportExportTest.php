<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use BayAreaWebPro\SimpleCsv\{Tests\Fakes\FakeModel, Tests\TestCase, SimpleCsv};
use Illuminate\Support\{Collection, LazyCollection, Facades\File};
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportExportTest extends TestCase
{
    public function test_accepted_types(): void
    {
        $items = $this->getCollectionData(10);

        // Array
        $path = $this->getRandomStoragePath();
        SimpleCsv::export($items->toArray(), $path);
        $this->assertFileExists($path);

        // Collection
        $path = $this->getRandomStoragePath();
        SimpleCsv::export($items->collect(), $path);
        $this->assertFileExists($path);

        // Lazy Collection
        $path = $this->getRandomStoragePath();
        SimpleCsv::export($items, $path);
        $this->assertFileExists($path);
    }

    public function test_import_file(): void
    {
        $collection = $this->getCollectionData();
        $path = $this->getRandomStoragePath();

        SimpleCsv::export($collection, $path);
        $this->assertFileExists($path);

        $fileData = File::get($path);
        foreach ($collection as $item) {
            $this->assertStringContainsString($item['uuid'], $fileData);
        }

        $decoded = SimpleCsv::import($path);
        $this->assertInstanceOf(LazyCollection::class, $decoded);

        foreach ($collection as $item) {
            $this->assertTrue($decoded->where('uuid', $item['uuid'])->count() === 1);
        }
    }

    public function test_can_download_streams(): void
    {
        $collection = $this->getCollectionData();

        $response = SimpleCsv::download($collection, 'download.csv');

        $this->assertInstanceOf(StreamedResponse::class, $response);

        ob_start();
        $response->sendContent();
        $data = (string)ob_get_clean();
        $this->assertNotEmpty($data);

        foreach ($collection as $item) {
            $this->assertStringContainsString($item['email'], $data);
        }
    }

    public function test_flattens_arrayable(): void
    {
        $collection = Collection::make([
            new FakeModel(),
        ]);

        $path = $this->getRandomStoragePath();
        SimpleCsv::export($collection, $path);
        $this->assertFileExists($path);

        $fileData = File::get($path);
        foreach ($collection as $item) {
            $this->assertStringContainsString($item->uuid, $fileData);
        }
    }
}
