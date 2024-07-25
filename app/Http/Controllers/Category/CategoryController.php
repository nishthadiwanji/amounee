<?php

namespace App\Http\Controllers\Category;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Category\CategoryRepository;
use App\Repositories\Artisan\ArtisanRepository;
use App\Http\Requests\Category\CommissionRequest;
use App\Models\Category\Category;
use App\Models\Artisan\Artisan;
use DB;
use Sentinel;

class CategoryController extends Controller
{

    protected $category_repo, $artisan_repo;

    public function __construct(CategoryRepository $category_repo, ArtisanRepository $artisan_repo)
    {
        $this->category_repo = $category_repo;
        $this->artisan_repo = $artisan_repo;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = $this->getPaginationRecordCount(request());
        $search = request()->has('search') ? request()->search : NULL; 
        $categories = $this->category_repo->search($search);
        $categories = $categories->with(['parent'])->paginate($records);
        return view('modules.category.index', compact('records', 'categories', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $id = decrypt_id_info($id);
        $category = $this->category_repo->getInformation($id);
        if(!$category){
            abort(404);
        }
        $sub_category_token = request()->sub_category_token;
        $sub_category = $this->category_repo->getInformation(decrypt_id_info($sub_category_token));
        $commission_histories=$sub_category->commissions()->orderBy('updated_at','desc')->get();
        return view('modules.category.show', compact('category','sub_category','commission_histories'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function storeCommission(CommissionRequest $request, $id)
    {
        $category = $this->category_repo->getInformation(decrypt_id_info($id));
        if(!$category){
            return $this->responseNotFound(__('responses.category_not_found'), ['title' => __('variable.sorry')]);
        }
        DB::beginTransaction();
        $response = $this->category_repo->createCommission($category, $request->all());
        if(!$response){
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update category']), ['title' => __('variable.sorry')]);
        }
        $response = $this->category_repo->createCommissionHistory($category, $request->all());
        if(!$response){
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update category']), ['title' => __('variable.sorry')]);
        }
        
        DB::commit();

        log_activity_by_user('category_log',$category,__('activity_log_messages.category_commission'));

        return $this->responseSuccessWithData(['title' => __('variable.great'), 'message' => __('responses.update_category')]);
    }
    public function fetchdetails($id)
    {
        $id = decrypt_id_info($id);
        $category = $this->category_repo->getInformation($id);
        if(!$category){
            abort(404);
        }
        return $category;
    }
}
