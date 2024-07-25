<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Exports\PaymentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\Exportable;

class ExportsController extends Controller
{
    use Exportable;
    public function export() 
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }
}
