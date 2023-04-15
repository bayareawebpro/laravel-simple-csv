<?php declare(strict_types=1);

namespace BayAreaWebPro\SimpleCsv\Casts;

class NumericValues
{
    public function __invoke(array $item): array
    {
        foreach ($item as $key => $value){
            if(is_numeric($value)){
                if(str_contains($value, '.')){
                    $item[$key] = floatval($value);
                    continue;
                }
                $item[$key] = intval($value);
            }
        }
        return $item;
    }
}