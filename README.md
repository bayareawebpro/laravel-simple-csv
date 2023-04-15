# Laravel Simple CSV

![](https://github.com/bayareawebpro/laravel-simple-csv/workflows/ci/badge.svg)
![](https://codecov.io/gh/bayareawebpro/laravel-simple-csv/branch/master/graph/badge.svg)
![](https://img.shields.io/github/v/release/bayareawebpro/laravel-simple-csv.svg)
![](https://img.shields.io/packagist/dt/bayareawebpro/laravel-simple-csv.svg)
![](https://img.shields.io/badge/License-MIT-success.svg)

> https://packagist.org/packages/bayareawebpro/laravel-simple-csv

## Features
- Import to LazyCollection.
- Export from Collection, LazyCollection, Iterable, Generator, Array.
- Low(er) Memory Consumption by use of LazyCollection Generators.
- Uses Native PHP SplFileObject.
- Facade Included.

## Installation
Simply require the package and Laravel will Auto-Discover the Service Provider.
```
composer require bayareawebpro/laravel-simple-csv
```

## Usage:

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;
use BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull;
use BayAreaWebPro\SimpleCsv\Casts\NumericValues;

$lazyCsvCollection = SimpleCsv::import(storage_path('collection.csv'), [
    EmptyValuesToNull::class,
    NumericValues::class,
]);
```

### Invokable Cast Classes

```php
<?php declare(strict_types=1);

namespace App\Csv\Casts;

use Carbon\Carbon;

class Timestamps
{
    /** Invoked for each row in import collection. */
    public function __invoke(array $item): array
    {
        foreach ($item as $key => $value){
            if(in_array($key, ['created_at', 'updated_at'])){
                $item[$key] = Carbon::parse($value);
            }
        }
        return $item;
    }
}
```

### Export to File
```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;

// Collection
SimpleCsv::export(
    Collection::make(...),
    storage_path('collection.csv')
);

// LazyCollection
SimpleCsv::export(
    LazyCollection::make(...),
    storage_path('collection.csv')
);

// Generator (Cursor)
SimpleCsv::export(
    User::query()->where(...)->limit(500)->cursor(),
    storage_path('collection.csv')
);

// Array
SimpleCsv::export(
    [...],
    storage_path('collection.csv')
);
```

### Export Download Stream

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;

return SimpleCsv::download([...], 'download.csv');
```

#### Override Options
```php
use Illuminate\Support\Facades\Config;

Config::set('simple-csv.delimiter', ...);
Config::set('simple-csv.enclosure', ...);
Config::set('simple-csv.escape', ...);
```

## Or, Create a Config File

`config/simple-csv.php`

```php
return [
    'delimiter' => '?',
    'enclosure' => '?',
    'escape'    => '?',
];
```

## File Splitting Utility
A file splitting utility has been included that will break large CSV files into chunks 
(while retaining column headers) which you can move/delete after importing. 
This can help with automating the import of large data sets.

Tip: Find your Bash Shell Binary Path: `which sh`

```
/bin/sh vendor/bayareawebpro/laravel-simple-csv/split-csv.sh /Projects/laravel/storage/big-file.csv 5000

File Output:
/Projects/laravel/storage/big-file-chunk-1.csv (chunk of 5000)
/Projects/laravel/storage/big-file-chunk-2.csv (chunk of 5000)
/Projects/laravel/storage/big-file-chunk-3.csv (chunk of 5000)
etc...
```

## Speed Tips
- Using Lazy Collections is the preferred method.
- Using the queue worker, you can import a several thousand rows at a time without much impact.
- Be sure to use "Database Transactions" and "Timeout Detection" to insure safe imports.
- [Article: How to Insert & Update Many at Once](https://medium.com/@danielalvidrez/laravel-query-builder-macros-fe176d34135e)