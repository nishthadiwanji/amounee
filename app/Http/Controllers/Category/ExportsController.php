<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\CategoriesExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportsController extends Controller
{
    use Exportable;
    public function import(Request $request)
    {
        if ($request->file('imported_file')) {
            Excel::import(new CategoriesImport(), request()->file('imported_file'));
            return back();
        }
    }

    public function export() 
    {
        return Excel::download(new CategoriesExport, 'categories.xlsx');
    }
}
