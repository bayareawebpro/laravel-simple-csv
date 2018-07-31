![Laravel Simple CSV](https://cdn.rawgit.com/bayareawebpro/laravel-simple-csv/97d15ca6/screenshot.png "Laravel Simple CSV")

# Simple CSV for Laravel
[![Generic badge](https://img.shields.io/badge/Build-Passing-ok.svg)]()
[![Generic badge](https://img.shields.io/badge/License-MIT-orange.svg)]()
[![Generic badge](https://img.shields.io/badge/Version-1.0-blue.svg)]()

## Features
- Import to Collection - Export from Collection.
- Low(er) Memory Consumption by use of Generators.
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

### Export from Collection to File
1) Pass a collection to the export method, which returns the chained save method. 
2) Specify the path when you call the save method.
```
use SimpleCsv;
use DB;

$collection = DB::table('users')->get(['id', 'name', 'email']);
$exporter = SimpleCsv::export($collection);
$exporter->save(storage_path('table.csv'));
```
### Export from Collection to Download Stream
1) Pass a collection to the export method, which returns the chained download response streamer. 
2) Specify the filename when you call the download method.
3) Return the response provided by the download method.
```
use SimpleCsv;
use DB;

public function download(Request $request)
{
    $collection = DB::table('users')->get(['id', 'name', 'email']);
    $exporter = SimpleCsv::export($collection);
    return $exporter->download('table.csv');
}
```

#### Extended Options
```
SimpleCsv::import($path = '/some/file.csv', $delimiter = ",", $enclosure = "\"", $escape = "\\");
SimpleCsv::export($collection, $delimiter = ",", $enclosure = "\"", $escape = "\\");
```

## File Splitting Utility
A file splitting utility has been included that will break large CSV files into chunks 
(while retaining column headers) which you can move/delete after importing. 
This can help with automating the import of large data sets.

Tip: Find your Bash Shell Binary Path: ``$ which sh``

```
/bin/sh vendor/bayareawebpro/laravel-simple-csv/split-csv.sh /Projects/laravel/storage/big-file.csv 5000

File Output:
/Projects/laravel/storage/big-file-chunk-1.csv (chunk of 5000)
/Projects/laravel/storage/big-file-chunk-2.csv (chunk of 5000)
/Projects/laravel/storage/big-file-chunk-3.csv (chunk of 5000)
etc...
```

## Speed Tips
- Queries are faster when you specify fields in the `get()` or `select()` method.
- Using the DB Facade instead of Eloquent can yield faster results.
- Using the queue worker, you can import a several thousand rows at a time without much impact.
- Be sure to use "Database Transactions", "Chunking" and "Timeout Detection" to insure safe imports.
- [Article: How to Insert & Update Many at Once](https://medium.com/@danielalvidrez/laravel-query-builder-macros-fe176d34135e)

### Chunk Export:
Chunk Query into multiple 10,000 row files and get the download paths.
```
$paths = [];
$query = DB::table('users')->select(['id', 'name', 'email'])->orderBy('id');
$query->chunk(10000, function($chunk, $index){
    $path = storage_path("chunk-{$index}.csv");
    SimpleCsv::export($chunk)->save($path);
    array_push($paths, $path)
});

File Output:
chunk-1.csv
chunk-2.csv
chunk-3.csv
...
```

### Chunk Import
Import file into the database, but group the insert queries as chunks.
```
DB::transaction(function() use ($chunks){
    $chunks = SimpleCsv::import(storage_path('table.csv'))->chunk(1000);
    $chunks->each(function($chunk){
        DB::insert($chunk->toArray());
    });
});
```

#### DebugBar Timeline Results:
- Import: 53,330 Rows in 951.5ms @ 63.42MB
- Export: 53,330 Rows in 821.64ms @ 47.12MB (regular query)
- Export: 218,443 Rows in 11.06secs @ 18.39MB (with query chunks)

Test Machine: *MacPro (3.1) 2.8Ghz (Dual) Quad Core / 18GB 800Mhz FB-DIM Memory / SSD*