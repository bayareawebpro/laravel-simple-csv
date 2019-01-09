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
SimpleCsv::import($path = '/some/file.csv', $callback = function, $chunk = 500, $delimiter = ",", $enclosure = "\"", $escape = "\\");
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
DB::transaction(function(){
    $chunks = SimpleCsv::import(storage_path('table.csv'))->chunk(1000);
    $chunks->each(function($chunk){
        DB::insert($chunk->toArray());
    });
});
```

Expose chunk callback to generator allowing lower memory consumption for large files:
```
DB::transaction(function(){
    SimpleCsv::import(storage_path('table.csv'), function($chunk){
       DB::insert($chunk->toArray());
    }, 1000);
});
```

#### DebugBar Timeline Results:

| Operation | Chunk  | Total Entries | Total Memory | Framework Memory | Actual Memory | Total Time | Framework Time | Actual Time |
| :-------: | :----: | :-----------: | :----------: | :--------------: | :-----------: | :--------: | :------------: | :---------: | 
| Import	| 10,000 | 10,000        | 15.2         | 8.09             | 7.11          | 0.311      | 0.166          | 0.145       |
| Export	| 10,000 | 10,000        | 18.3         | 8.09             | 10.21         | 0.484      | 0.166          | 0.318       |
| Export	| 10,000 | 221,443       | 18.09	    | 8.09             | 10            | 11.21      | 0.166          | 11.044      |

Test Machine: *MacPro (3.1) 2.8Ghz (Dual) Quad Core / 18GB 800Mhz FB-DIM Memory / SSD*