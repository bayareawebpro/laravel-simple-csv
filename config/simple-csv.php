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
    | CSV Cast Classes
    |--------------------------------------------------------------------------
    |
    | Here you may specify the classes for casting values.
    |
    */

    'casts'    => [
        \BayAreaWebPro\SimpleCsv\Casts\EmptyValuesToNull::class,
        \BayAreaWebPro\SimpleCsv\Casts\NumericValues::class,
    ],
];