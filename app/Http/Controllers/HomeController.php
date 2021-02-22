<?php

namespace App\Http\Controllers;

use App\Imports\ExcelFile;
use Excel;
class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $example_path = public_path('arkusz.xlsx');
        $import = new ExcelFile;
        Excel::import($import, $example_path);
        $data = $import->getData();
        
        foreach ($data as $index => $element) {
            foreach ($element as $key => $value) {
                $data[$index][$key] = str_replace(' ', "/s", $data[$index][$key]);
            }
        }

        return view('home', compact('data'));
    }
}
