<?php

namespace App\Http\Controllers;

use App\Imports\ExcelFile;
use Excel;
use URL;

class HomeController extends Controller
{
    public function index()
    {
        $example_path = public_path('arkusz.xlsx');
        $import = new ExcelFile;

        if (is_file($example_path)) {
            Excel::import($import, $example_path);
        } else {
            return view('error');
        }
        
        
        $data = $import->getData();
        
        foreach ($data as $index => $element) {
            foreach ($element as $key => $value) {
                $data[$index][$key] = str_replace(' ', "/s", $data[$index][$key]);
            }
        }

        return view('home', compact('data'));
    }

    public function download() 
    {
        $example_pdf = URL::to('/').'/sample_file.pdf';

        return response()->json(['file' => $example_pdf]);
    }
}
