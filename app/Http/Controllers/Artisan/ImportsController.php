<?php

namespace App\Http\Controllers\Artisan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ArtisansImport;
use App\Repositories\Artisan\ArtisanRepository;
use Exception;
use Sentinel;
use Log;
use DB;

class ImportsController extends Controller
{
    protected $artisan_repo;
     public function __construct(ArtisanRepository $artisan_repo)
    {
        $this->artisan_repo=$artisan_repo;
    }
    public function index()
    {
        return view('modules.artisan._partials.artisan-import');
    }

    /**
     * Import function
     */
    public function import(Request $request)
    {
        try
        {
            $file=$request->file('file');
            if ($file) {
                Excel::import(new ArtisansImport($this->artisan_repo),$file);
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.import_artisan')],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e) {
            DB::rollBack();
            Log::error(__('log.add_artisan_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create artisan'])],200)->setCallback(request()->input('callback'));
        }
    }
}
