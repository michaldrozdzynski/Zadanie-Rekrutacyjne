<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelFile implements ToCollection
{
    private $data = [];

    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $array = [];
        $keys = [];

        $filtered = $collection[0]->filter(function ($value, $key) use (&$keys) {
            if (explode(':', $value)[0] === 'DPO_Filter') {
                $value = explode(':', $value)[1];
                array_push($keys, $key);
                return true;
            } else {
                return false;
            }
        });

        $filtered = $filtered->all();

        foreach ($filtered as $key => $value) {
                $filtered[$key] = explode(':', $value)[1];
        }

        array_push($array, $filtered);

        $length = count($collection);

        for ($i = 1; $i < $length; $i++) {
            $filtered = $collection[$i]->filter(function ($value, $key) use ($keys) {
                return in_array($key, $keys);
            });
            array_push($array, $filtered->all());
        }

        while ($this->isMultipleValueInCell($length, $array)) {}
        array_push($this->data, $array);;
        //dd($array);
    }

    public function getData() {
        return $this->data[0];
    }

    private function isMultipleValueInCell($length, &$array) {
        for ($i = 1; $i < $length; $i++) {
            foreach ($array[$i] as $key => $value) {
                $explode = explode(', ', $value);
                if (count($explode) > 1){
                    $this->splitTheCell($array, $length, $i, $key);
                    return true;    
                }
            }
        }

        return false;
    }

    private function splitTheCell(&$array, $length, $index, $key) {
        $explode = explode(', ', $array[$index][$key], 2);
        $temp = $array[$index];
        $temp[$key] = $explode[1];
        $array[$index][$key] = $explode[0];

        for ($i = $index+1; $i < $length+1; $i++) {
            if ($i >= $length) {
                $array[$i] = $temp;
            } else {
                $temp2 = $array[$i];
                $array[$i] = $temp;
                $temp = $temp2;
            }
        }
    }
}
