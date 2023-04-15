<?php namespace BayAreaWebPro\SimpleCsv;

use Illuminate\Support\Enumerable;
use Illuminate\Support\LazyCollection;

final class LazyCsvCollection extends LazyCollection
{
    /**
     * Convert empty item array keys to null.
     */
    public function emptyToNull()
    {
        return new static(function () {
            foreach ($this as $item){
                if(is_array($item) || $item instanceof Enumerable){
                    foreach ($item as $key => $value){
                        if(empty($value)){
                            $item[$key] = null;
                        }
                    }
                }
                yield $item;
            }
        });
    }
}