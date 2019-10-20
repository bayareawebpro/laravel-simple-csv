<?php
namespace BayAreaWebPro\SimpleCsv\Tests;

use Illuminate\Support\LazyCollection;

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