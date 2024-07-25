<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportsController extends Controller
{
    use Exportable;
    public function import(Request $request)
    {
        if ($request->file('imported_file')) {
            Excel::import(new ProductsImport(), request()->file('imported_file'));
            return back();
        }
    }

    public function export() 
    {
        return Excel::download(new ProductsExport, 'products.xlsx');
    }
}
