<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Casts;

class EmptyValuesToNull
{
    public function __invoke(array $item): array
    {
        foreach ($item as $key => $value){
            if(empty($value)){
                $item[$key] = null;
            }
        }
        return $item;
    }
}