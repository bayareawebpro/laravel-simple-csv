<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Tests\Fakes;

use Illuminate\Contracts\Support\Arrayable;

class FakeModel implements Arrayable
{
    public string $uuid;
    public function __construct()
    {
        $faker = \Faker\Factory::create();
        $this->uuid = (string)$faker->uuid;
    }
    public function toArray(): array
    {
        return [
            'uuid'  => $this->uuid,
        ];
    }
}