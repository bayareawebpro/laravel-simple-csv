# Simple CSV for Laravel

[![Generic badge](https://img.shields.io/badge/Build-Passing-ok.svg)]()
[![Generic badge](https://img.shields.io/badge/License-MIT-green.svg)]()
[![Generic badge](https://img.shields.io/badge/Version-2.0-blue.svg)]()

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

### Import to Collection
1) Pass a path to the import method which returns the collection of parsed entries.
```
use SimpleCsv;

$collection = SimpleCsv::import(storage_path('table.csv'));
```
___

### Import
```
$lazyCollection = SimpleCsv::import(storage_path('collection.csv'));
```

### Export to File
```
use SimpleCsv;

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
```
use SimpleCsv;
public function download(Request $request)
{
    $collection = LazyCollection::make(...);
    return SimpleCsv::download($collection, 'download.csv');
}
```

#### Extended Options
```
SimpleCsv::make($delimter, $enclosure, $escape)->export(...);
SimpleCsv::make($delimter, $enclosure, $escape)->import(...);
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