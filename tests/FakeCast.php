<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests;

class FakeCast
{
    public function __invoke(array $item): array
    {
        return $item;
    }
}