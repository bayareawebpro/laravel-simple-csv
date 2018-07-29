![Laravel Simple CSV](https://cdn.rawgit.com/bayareawebpro/laravel-simple-csv/f1418824/screenshot.png "Logo Title Text 1")

# Simple CSV for Laravel
- Import to Collection - Export from Collection.
- Low(er) Memory Consumption by use of Generators.
- Uses Native PHP SplFileObject.
- Facades Included.

## Usage:

### Default Options for Delimiter, Enclosure & Escape
```
SimpleCsv::import($path = '/some/file.csv', $delimiter = ",", $enclosure = "\"", $escape = "\\");
SimpleCsv::export($collection, $delimiter = ",", $enclosure = "\"", $escape = "\\");
```

### Import to Collection
Specify the path when calling the import method on the facade.
```
use SimpleCsv;

$collection = SimpleCsv::import(storage_path('table.csv'));
```
### Export from Collection to File
Pass a collection in the export method.
Specify the path when you call the chained save method.
```
use SimpleCsv;
use DB;

$collection = DB::table('users')->get(['id', 'name', 'email']);

$exporter = SimpleCsv::export($collection);

$exporter->save(storage_path('table.csv'));
```
### Export from Collection to Download Stream
```
use SimpleCsv;
use DB;

Route::get('/download-csv', function() {

    $collection = DB::table('users')->get(['id', 'name', 'email']);

    $exporter = SimpleCsv::export($collection);
    
    return $export->download('table.csv');
});

```

#### Speed Tips
- Queries are much faster if you specify the exact fields needed in the `get()` method.
- Using the DB Facade instead of Eloquent can yield faster results.
- Using the queue worker, you can import a several thousand rows at a time without much impact.
- Be sure to use "Database Transactions", "Chunking" and "Timeout Detection" to insure safe imports.

#### PhpUnit Results:
- OK (4 tests, 5 assertions)
- Source Collection Values match Import Collection Values

#### Documentation:
[View the API Documentation](https://cdn.rawgit.com/bayareawebpro/laravel-simple-csv/1737e6a4/_docs/index.html)


#### DebugBar Timeline Results:
Here's an extreme example that show what's possible in under 1 second when we push things to the limit.

- Import: 53,330 Rows in 951.5ms @ 63.42MB
- Export: 53,330 Rows in 821.64ms @ 47.12MB

*Test Machine: MacPro (3.1) 2.8Ghz (Dual) Quad Core / 18GB 800Mhz FB-DIM Memory / SSD*

