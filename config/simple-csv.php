<?php
return [
    /*
    |--------------------------------------------------------------------------
    | CSV Delimiter Character
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CSV delimiter character.
    |
    */

    'delimiter' => ',',

    /*
    |--------------------------------------------------------------------------
    | CSV Enclosure Character
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CSV enclosure character.
    |
    */

    'enclosure' => '"',

    /*
    |--------------------------------------------------------------------------
    | CSV Escape Character
    |--------------------------------------------------------------------------
    |
    | Here you may specify the CSV escape character.
    |
    */

    'escape'    => '\\',

    /*
    |--------------------------------------------------------------------------
    | Default CSV Cast Classes
    |--------------------------------------------------------------------------
    |
    | Here you may specify the default classes for casting values. Casts
    | specified in the import method will be merged with these defaults
    | to create a unique set of casts for the operation.
    |
    */

    'casts'    => [
        \BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull::class,
        \BayAreaWebPro\SimpleCsv\Casts\NumericValues::class,
    ],
];