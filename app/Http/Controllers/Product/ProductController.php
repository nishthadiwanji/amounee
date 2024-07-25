<?php

namespace App\Http\Controllers\Product;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Artisan\ArtisanRepository;
use App\Http\Requests\Product\stockRequest;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Artisan\Artisan;
use App\Models\Category\Category;
use App\Models\Product\Product;
use App\Models\FileInfo\FileInfo;
use Carbon\Carbon;
use Exception;
use Sentinel;
use Log;
use DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected $product_repo;
    protected $artisan_repo;

    public function __construct(ProductRepository $product_repo, ArtisanRepository $artisan_repo)
    {
        $this->product_repo = $product_repo;
        $this->artisan_repo = $artisan_repo;
    }
    public function index($status_parameter=null)
    {
        $records = $this->getPaginationRecordCount(request());

        $artisans = Artisan::select('id','first_name','last_name')->get();
        $categories = Category::select('id','category_name')->get();

        $search = empty(request()->search) ?  '' : request()->search;
        $stock_search = empty(request()->stock_search) ? '' : request()->stock_search;
        $artisan_search = empty(request()->artisan_search) ? '' : request()->artisan_search;

        $status = empty(request()->status) ? 'pending' : request()->status;

        $status=empty($status_parameter)?$status:$status_parameter;

        $products = $this->product_repo->search($search,$stock_search,$artisan_search);
        // $products = $this->product_repo->searchStock($stock_search);
        if ($status == 'delisted') {
            $products = $products->delisted();
        } elseif($status == 'approved') {
            $products = $products->approve();
        } elseif($status == 'rejected') {
            $products = $products->rejected();
        } else {
            $products = $products->pending();
        }
        $products = $products->orderBy('id', 'desc')->paginate($records);

        //count rows.
        $total_pending_products = Product::where('status','pending')->count();
        $total_approved_products = Product::where('status','approved')->count();
        $total_rejected_products = Product::where('status','rejected')->count();
        $total_delisted_products = Product::where('status','delisted')->count();

        return view('modules.product.index',compact('products','artisans','categories','search','status','records','stock_search','artisan_search','total_pending_products','total_approved_products','total_rejected_products','total_delisted_products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $stock_status = collect(config('constant.stock_status'));
        $tax_status = collect(config('constant.tax_status'));
        $tax_class = collect(config('constant.tax_class'));
        $categories = Category::whereNull('parent_category_id')->select('id','category_name')->get();
        $sub_categories = Category::whereNotNull('parent_category_id')->select('id','category_name')->get();
        $artisans=Artisan::where('status','approved')->get();
        return view('modules.product.create',compact('categories','stock_status','sub_categories','tax_status','tax_class','artisans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        try{
            $file = null;
            if($request->hasFile('product_image')){

                $file = $request->file('product_image');
            }
            DB::beginTransaction();
            $attributes = $request->all();
            $attributes['category_id'] = decrypt_id_info($request->category_id); 
            $attributes['sub_category_id'] = decrypt_id_info($request->sub_category_id); 
            $attributes['artisan_id'] = decrypt_id_info($request->artisan_id); 
            $product = $this->product_repo->createProduct($file,$attributes);
            if (!$product) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_not_found')], 200)->setCallback(request()->input('callback'));
            }
            //create product images
            $result = $this->product_repo->createProductGallery($product, $request);
            if (!$result) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_gallery_images_not_uploaded')], 200)->setCallback(request()->input('callback'));
            }
            $stockHistory=$this->product_repo->createProductStockHistory($product,$attributes);
            if (!$stockHistory) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_stock_history_error')], 200)->setCallback(request()->input('callback'));
            }
            DB::commit();
            if($attributes['is_approved']==1)
            {
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.add_approve_product')],200)->setCallback(request()->input('callback'));
            }
            else
            {
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.add_product')],200)->setCallback(request()->input('callback'));
            }
        }catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.add_product_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create product'])],200)->setCallback(request()->input('callback'));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
         $id = decrypt_id_info($id);
        $product = $this->product_repo->getInformation($id);
        if(!$product){
            abort(404);
        }
        $product_main_image=$product->productImages()->where('file_type','main')->select('file_id')->first();
        $file_id=$product_main_image['file_id'];
        $product_image=FileInfo::where('id',$file_id)->first();
        $productPriceHistories=$product->productPriceHistories()->orderBy('updated_at','desc')->get();
        $productStockHistories=$product->productStockHistories()->orderBy('updated_at','desc')->get();
        return view('modules.product.show', compact('product','productPriceHistories','productStockHistories','product_image'));
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
        $id = decrypt_id_info($id);
        $product = Product::findOrFail($id);
        $stock_status = collect(config('constant.stock_status'));
        $tax_status = collect(config('constant.tax_status'));
        $tax_class = collect(config('constant.tax_class'));
        $categories = Category::whereNull('parent_category_id')->select('id','category_name')->get();
        $sub_categories = Category::whereNotNull('parent_category_id')->select('id','category_name')->get();
        $artisans=Artisan::where('status','approved')->get();
        $product_main_image=$product->productImages()->where('file_type','main')->select('file_id')->first();
        $file_id=$product_main_image['file_id'];
        $product_image=FileInfo::where('id',$file_id)->first();
        return view('modules.product.edit',compact('product','categories','stock_status','sub_categories','tax_status','tax_class','artisans','product_image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        try {
            $id = decrypt_id_info($id);
            $product = Product::find($id);
            $product_old_stock=$product->stock;
            $product_old_stock_status=$product->stock_status;
            if (!$product) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_not_found')], 200)->setCallback(request()->input('callback'));
            }
            $attributes = $request->all();
            $attributes['artisan_id'] = decrypt_id_info($request->artisan_id);
            $attributes['category_id'] = decrypt_id_info($request->category_id);
            $attributes['sub_category_id'] = decrypt_id_info($request->sub_category_id);
            $file = $request->file('product_image');
            DB::beginTransaction();
            $response = $this->product_repo->updateProduct($product, $file ,$attributes);
            if ($response == false) {
                DB::rollBack();
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'update product'])], 200)->setCallback(request()->input('callback'));
            }
            //update product gallery
            $result = $this->product_repo->updateImages($product, $request);
            if (!$result) {
                DB::rollBack();
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_gallery_images_not_uploaded')], 200)->setCallback(request()->input('callback'));
            }
            //if product price is updated then only enter in product price history
            // if($product_old_base_price!=$product->base_price)
            // {
            //     $pricehistory=$this->product_repo->createProductPriceHistory($product,$attributes);
            //     if (!$pricehistory) {
            //         return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_price_history_error')], 200)->setCallback(request()->input('callback'));
            //     }
            // }
            //if stock_status or stock updated then only enter in product stock history
            if($product_old_stock_status!=$product->stock_status || $product_old_stock!=$product->stock)
            {
                $stockHistory=$this->product_repo->createProductStockHistory($product,$attributes);
                if (!$stockHistory) {
                    return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.product_stock_history_error')], 200)->setCallback(request()->input('callback'));
                }
            }
            DB::commit();
            return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => __('responses.update_product')], 200)->setCallback(request()->input('callback'));
         } catch (Exception $e) {
            DB::rollBack();
            Log::error(__('log.update_product_error'), [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'update Product'])], 200)->setCallback(request()->input('callback'));
        }
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

    public function updateStock(stockRequest $request, $id)
    {
        $product = $this->product_repo->getInformation(decrypt_id_info($id));
        if(!$product){
            return $this->responseNotFound(__('responses.product_not_found'), ['title' => __('variable.sorry')]);
        }
        DB::beginTransaction();
        $response = $this->product_repo->manageStock($product, $request->all());
        if(!$response){
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update product']), ['title' => __('variable.sorry')]);
        }
        DB::commit();

        log_activity_by_user('product_log',$product,__('activity_log_messages.manage_stock'));

        return $this->responseSuccessWithData(['title' => __('variable.great'), 'message' => __('responses.update_product')]);
    }

    public function approve($id)
    {
        $id = decrypt_id_info($id);
        $product = $this->product_repo->getInformation($id);
         try{
            DB::beginTransaction();
            $response = $this->product_repo->approveProduct($id);
            if($response){
                DB::commit();

                log_activity_by_user('product_log',$product,__('activity_log_messages.approved_product'));

                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.activate_product')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.activate_product_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'approve product'])],200)->setCallback(request()->input('callback'));
             }
         }catch(Exception $e){
             DB::rollBack();
             Log::error(__('log.activate_product_error'),[$e->getMessage()]);
             return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'approve product'])],200)->setCallback(request()->input('callback'));
         }
    }

    public function reject($id)
    {
        $id = decrypt_id_info($id);
        $product = $this->product_repo->getInformation($id);
        try{
            $response = $this->product_repo->rejectProduct($id);
            if($response){
                DB::commit();

                log_activity_by_user('product_log',$product,__('activity_log_messages.rejected_product'));

                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.reject_product')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.reject_product_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'reject product'])],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.reject_product_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'reject product'])],200)->setCallback(request()->input('callback'));
        }
    }

    public function banProduct($id)
    {
        $product = $this->product_repo->getInformation(decrypt_id_info($id));
        if (!$product) {
            return $this->responseNotFound(__('responses.product_not_found'), ['title' => __('variable.sorry')]);
        }
        try {
            DB::beginTransaction();
            $result = $this->product_repo->banProduct($product);
            if ($result) {
                DB::commit();
                return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => "Product delisted successfully"], 200)->setCallback(request()->input('callback'));
            } else {
                DB::rollBack();
                Log::error(__('log.delist_product_error'), [$id]);
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'delist product'])], 200)->setCallback(request()->input('callback'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while delisting product', [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'delist product'])], 200)->setCallback(request()->input('callback'));
        }
    }

    public function unbanProduct($id)
    {
        $product = $this->product_repo->getInformation(decrypt_id_info($id));
        if (!$product) {
            return $this->responseNotFound(__('responses.product_not_found'), ['title' => __('variable.sorry')]);
        }
        try {
            DB::beginTransaction();
            $result = $this->product_repo->unbanProduct($product);
            if ($result) {
                DB::commit();
                return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => "Product listed successfully"], 200)->setCallback(request()->input('callback'));
            } else {
                DB::rollBack();
                Log::error(__('log.list_product_error'), [$id]);
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'list product'])], 200)->setCallback(request()->input('callback'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while listing product', [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'list product'])], 200)->setCallback(request()->input('callback'));
        }
    }
}
