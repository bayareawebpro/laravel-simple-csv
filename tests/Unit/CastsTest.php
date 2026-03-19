<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Unit;

use BayAreaWebPro\SimpleCsv\{
    Casts\EmptyValuesToNull,
    Casts\NumericValues,
    Tests\Fakes\FakeCast,
    Tests\TestCase,
    SimpleCsv
};
use Illuminate\Config\Repository;

class CastsTest extends TestCase
{
    protected function defineEnvironment($app): void
    {
        tap($app['config'], function (Repository $config) {
            $config->set('simple-csv.casts', [
                FakeCast::class
            ]);
        });
    }

    public function test_import_casts(): void
    {
        $items = $this->getCollectionData(5)->toArray();

        $path = $this->getRandomStoragePath();

        SimpleCsv::export($items, $path);

        $this->assertFileExists($path);

        $items = SimpleCsv::import($path);

        foreach($items as $item){
            $this->assertIsString($item['float']);
            $this->assertEmpty($item['empty']);
            $this->assertIsString($item['int']);
        }

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
}
