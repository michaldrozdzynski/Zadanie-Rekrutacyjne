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
        Excel::import(new ExcelFile, $example_path);
      

        return view('home', ['path' => $example_path]);
    }
}
