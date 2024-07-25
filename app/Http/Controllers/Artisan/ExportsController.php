<?php

namespace App\Http\Controllers\Artisan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ArtisansExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportsController extends Controller
{
    use Exportable;
    // public function index()
    // {
    //     $artisans = Artisan::all();

    //     return view('artisans')->with('artisans', $artisans);
    // }

    /**
     * Import function
     */
    public function import(Request $request)
    {
        if ($request->file('imported_file')) {
            Excel::import(new ArtisansImport(), request()->file('imported_file'));
            return back();
        }
    }

    public function export() 
    {
        return Excel::download(new ArtisansExport, 'artisans.xlsx');
    }
}
