# Laravel Simple CSV

![](https://github.com/bayareawebpro/laravel-simple-csv/workflows/ci/badge.svg)
![](https://codecov.io/gh/bayareawebpro/laravel-simple-csv/branch/master/graph/badge.svg)
![](https://img.shields.io/github/v/release/bayareawebpro/laravel-simple-csv.svg)
![](https://img.shields.io/packagist/dt/bayareawebpro/laravel-simple-csv.svg)
![](https://img.shields.io/badge/License-MIT-success.svg)

> https://packagist.org/packages/bayareawebpro/laravel-simple-csv

## Features

- Import to LazyCollection.
- Export from Collection, LazyCollection, Generator, Array, Iterable.
- Low(er) Memory Consumption by use of Generators.

## Installation

Require the package and Laravel will Auto-Discover the Service Provider.

```
composer require bayareawebpro/laravel-simple-csv
```

### Publish Config

Publish the config file to customize the default behavior.

```shell
artisan vendor:publish --tag=simple-csv
```

## Import Usage

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;

$lazyCollection = SimpleCsv::import(storage_path('collection.csv'));
```

### Formatting Casts

Invokable classes can be passed to the import method allowing you to customize
how each row is processed.

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;
use App\SimpleCsv\MyCustomCast;

$lazyCollection = SimpleCsv::import(storage_path('collection.csv'), [
    MyCustomCast::class
]);
```

### Default Casts

Two cast classes to handle numerics and null values are configured by default 
and can be overridden in the config file. Casts passed to the import method 
will be merged with defaults.

```php
use BayAreaWebPro\SimpleCsv\Casts\NumericValues;
use BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull;
```

### Example Cast Class

You can typehint required dependencies in a constructor
method when required.

```php
<?php declare(strict_types=1);

namespace App\Csv\Casts;

use Carbon\Carbon;
use Illuminate\Config\Repository;

class ParseTimestamps
{
    /** 
     * Inject services by adding a constructor. 
     */
    public function __construct(public Repository $config)
    {
        // 
    }
    
    /** 
     * Invoked for each row. 
     */
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

## Export Usage

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;

// Collection
SimpleCsv::export(
    items: Collection::make(...),
    path: storage_path('collection.csv')
);

// LazyCollection
SimpleCsv::export(
    items: LazyCollection::make(...),
    path: storage_path('collection.csv')
);

// Generator (Cursor)
SimpleCsv::export(
    items: User::query()->where(...)->limit(500)->cursor(),
    path: storage_path('collection.csv')
);

// Array
SimpleCsv::export(
    items: [...],
    path: storage_path('collection.csv')
);
```

### Download Stream

You can simply return the service from a route to create a steamed download.

> Optional response headers can be passed as the 3rd argument.

```php
use BayAreaWebPro\SimpleCsv\SimpleCsv;
use Symfony\Component\HttpFoundation\StreamedResponse;

public function download(): StreamedResponse
{
    return SimpleCsv::download(
        items: [...], 
        fileName: 'download.csv', 
        headers: ['My-Response-Header' => 'some-value']
    );
}
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