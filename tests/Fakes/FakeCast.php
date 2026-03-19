<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Fakes;

use Illuminate\Config\Repository;

class FakeCast
{

    public function __construct(public Repository $config)
    {

    }
    public function __invoke(array $item): array
    {
        return $item;
    }
}