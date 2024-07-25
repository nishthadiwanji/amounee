<?php

namespace App\Http\Controllers\Artisan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Artisan\ArtisanRepository;
use App\Repositories\Product\ProductRepository;
use App\Http\Requests\Artisan\ArtisanRequest;
use App\Http\Requests\Artisan\ApprovalNoteRequest;
use App\Http\Requests\Artisan\RejectionNoteRequest;
use App\Models\Artisan\Artisan;
use App\Models\Category\Category;
use App\Models\Product\Product;
use Carbon\Carbon;
use Exception;
use Sentinel;
use Log;
use DB;
use App\Transformers\ArtisanTransformer;

class ArtisanController extends Controller
{
    protected $artisan_repo;
    protected $product_repo;
    protected $transformer;

    public function __construct(ArtisanRepository $artisan_repo, ArtisanTransformer $transformer, ProductRepository $product_repo)
    {
        // $this->middleware('pin.checkrole:admin',['only'=>['index','show','create','store','edit','update','destroy','restore', 'approve','reject','downloadAllartisansAsExcel']]);
        $this->artisan_repo = $artisan_repo;
        $this->product_repo = $product_repo;
        $this->transformer = $transformer;
    }


    public function index($status_parameter=null)
    {
        $records = $this->getPaginationRecordCount(request());
        $search = empty(request()->search) ?  '' : request()->search;
        $status = empty(request()->status) ? 'pending' : request()->status;
        $status=empty($status_parameter)?$status:$status_parameter;
        $artisans = $this->artisan_repo->search($search);
        if ($status == 'banned') {
            $artisans = $artisans->banned();
        } elseif($status == 'approved') {
            $artisans = $artisans->approve();
        } elseif($status == 'rejected') {
            $artisans = $artisans->rejected();
        } else {
            $artisans = $artisans->pending();
        }
        $artisans = $artisans->orderBy('id', 'desc')->paginate($records);
        return view('modules.artisan.index',compact('artisans','search','status','records'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // $categories = collect(config('constant.categories_option'));
        $categories = Category::whereNull('parent_category_id')->select('id','category_name')->get();
        return view('modules.artisan.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ArtisanRequest $request)
    {
        $is_approved = request()->is_approved;
        try {
            $attributes = $request->all();
            $attributes['category_id'] = decrypt_id_info($request->category_id); 
            DB::beginTransaction();
            $artisan = $this->artisan_repo->createArtisan($attributes, Sentinel::getUser()->id, $is_approved);
            if (!$artisan) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_not_created')], 200)->setCallback(request()->input('callback'));
            }
            //create artisan status
           $status= $this->artisan_repo->createArtisanStatus($artisan, 'pending', $is_approved);
           if (!$status) {
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_status_not_created')], 200)->setCallback(request()->input('callback'));
        }
            //create artisan files
            $result = $this->artisan_repo->createArtisanFiles($artisan, $request);
            if (!$result) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_file_not_uploaded')], 200)->setCallback(request()->input('callback'));
            }
            DB::commit();
            return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.add_artisan')],200)->setCallback(request()->input('callback'));
        } catch(Exception $e) {
            DB::rollBack();
            Log::error(__('log.add_artisan_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create artisan'])],200)->setCallback(request()->input('callback'));
        }
    }

    public function storeApprovalNote(ApprovalNoteRequest $request, $id)
    {
        $artisan = $this->artisan_repo->getInformation(decrypt_id_info($id));
        if(!$artisan){
            return $this->responseNotFound(__('responses.artisan_not_found'), ['title' => __('variable.sorry')]);
        }
        DB::beginTransaction();
        $response = $this->artisan_repo->createApprovalNote($artisan, $request->all());
        if(!$response){
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update artisan']), ['title' => __('variable.sorry')]);
        }
        //update status 
        $result = $this->artisan_repo->approveArtisan($artisan->id);
        if (!$result) {
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update artisan']), ['title' => __('variable.sorry')]);
        }
        //update status in artisan_status table
        $status= $this->artisan_repo->createArtisanStatus($artisan,'approved', '0');
        if (!$status) {
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_status_not_created')], 200)->setCallback(request()->input('callback'));
        }
        DB::commit();

        log_activity_by_user('artisan_log',$artisan,__('activity_log_messages.approved_artisan'));

        return $this->responseSuccessWithData(['title' => __('variable.great'), 'message' => __('responses.update_artisan')]);
    }

    public function storeRejectionNote(RejectionNoteRequest $request, $id)
    {
        $artisan = $this->artisan_repo->getInformation(decrypt_id_info($id));
        if(!$artisan){
            return $this->responseNotFound(__('responses.artisan_not_found'), ['title' => __('variable.sorry')]);
        }
        DB::beginTransaction();
        $response = $this->artisan_repo->createRejectionNote($artisan, $request->all());
        if(!$response){
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update artisan']), ['title' => __('variable.sorry')]);
        }
        //update status 
        $result = $this->artisan_repo->rejectArtisan($artisan->id);
        if (!$result) {
            DB::rollBack();
            return $this->responseFail(__('error.500', ['operation' => 'update artisan']), ['title' => __('variable.sorry')]);
        }
        //update status in artisan_status table
           //update status in artisan_status table
           $status= $this->artisan_repo->createArtisanStatus($artisan,'rejected', '0');
           if (!$status) {
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_status_not_created')], 200)->setCallback(request()->input('callback'));
        }
        DB::commit();

        log_activity_by_user('artisan_log',$artisan,__('activity_log_messages.rejected_artisan'));

        return $this->responseSuccessWithData(['title' => __('variable.great'), 'message' => __('responses.update_artisan')]);
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
        $artisan = $this->artisan_repo->getInformation($id);
        if(!$artisan){
            abort(404);
        }
        // $products = $this->product_repo->getInformation($id);
        // if(!$products){
        //     abort(404);
        // }
        $products=$artisan->product()->get();
        $artisanStatuses=$artisan->artisanStatuses()->orderBy('updated_at','desc')->get();
        return view('modules.artisan.show', compact('artisan','artisanStatuses','products'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $id = decrypt_id_info($id);
        // $artisan = Artisan::with(['photo', 'artisanFiles.fileInfo'])->findOrFail($id);
        $artisan = Artisan::findOrFail($id);
        $categories = Category::whereNull('parent_category_id')->select('id', 'category_name')->get();
        return view('modules.artisan.edit',compact('artisan', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ArtisanRequest $request, $id)
    {
        try {
            $id = decrypt_id_info($id);
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_not_found')], 200)->setCallback(request()->input('callback'));
            }
            $attributes = $request->all();
            $attributes['category_id'] = decrypt_id_info($request->category_id);
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisan($artisan, $attributes);
            if ($response == false) {
                DB::rollBack();
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'update artisan'])], 200)->setCallback(request()->input('callback'));
            }
            //create artisan files
            $result = $this->artisan_repo->updateArtisanFiles($artisan, $request);
            if (!$result) {
                DB::rollBack();
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_file_not_uploaded')], 200)->setCallback(request()->input('callback'));
            }
            DB::commit();
            return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => __('responses.update_artisan')], 200)->setCallback(request()->input('callback'));
         } catch (Exception $e) {
            DB::rollBack();
            Log::error(__('log.update_artisan_error'), [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'update artisan'])], 200)->setCallback(request()->input('callback'));
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
        $id = decrypt_id_info($id);
        try{
            $response = $this->artisan_repo->deleteArtisan($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.ban_artisan')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.ban_artisan_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'ban artisan'])],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.ban_artisan_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'ban artisan'])],200)->setCallback(request()->input('callback'));
        }
    }
    public function restore($id)
    {
        $id = decrypt_id_info($id);
         try{
            DB::beginTransaction();
            $response = $this->artisan_repo->restoreArtisan($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.activate_artisan')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.activate_artisan_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'activate artisan'])],200)->setCallback(request()->input('callback'));
             }
         }catch(Exception $e){
             DB::rollBack();
             Log::error(__('log.activate_artisan_error'),[$e->getMessage()]);
             return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'activate artisan'])],200)->setCallback(request()->input('callback'));
         }
    }
    public function approve($id)
    {
        $id = decrypt_id_info($id);
         try{
            DB::beginTransaction();
            $response = $this->artisan_repo->approveArtisan($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.activate_artisan')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.activate_artisan_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'approve artisan'])],200)->setCallback(request()->input('callback'));
             }
         }catch(Exception $e){
             DB::rollBack();
             Log::error(__('log.activate_artisan_error'),[$e->getMessage()]);
             return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'approve artisan'])],200)->setCallback(request()->input('callback'));
         }
    }
    public function reject($id)
    {
        $id = decrypt_id_info($id);
        try{
            $response = $this->artisan_repo->rejectArtisan($id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.reject_artisan')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.reject_artisan_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'reject artisan'])],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.reject_artisan_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'reject artisan'])],200)->setCallback(request()->input('callback'));
        }
    }

    public function banArtisan($id)
    {
        $artisan = $this->artisan_repo->getInformation(decrypt_id_info($id));
        if (!$artisan) {
            return $this->responseNotFound(__('responses.artisan_not_found'), ['title' => __('variable.sorry')]);
        }
        try {
            DB::beginTransaction();
            $result = $this->artisan_repo->banArtisan($artisan);
            if ($result) {
                DB::commit();
                return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => "Artisan banned successfully"], 200)->setCallback(request()->input('callback'));
            } else {
                DB::rollBack();
                Log::error(__('log.reject_artisan_error'), [$id]);
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'ban artisan'])], 200)->setCallback(request()->input('callback'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while banning artisan', [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'ban artisan'])], 200)->setCallback(request()->input('callback'));
        }
    }

    public function unbanArtisan($id)
    {
        $artisan = $this->artisan_repo->getInformation(decrypt_id_info($id));
        if (!$artisan) {
            return $this->responseNotFound(__('responses.artisan_not_found'), ['title' => __('variable.sorry')]);
        }
        try {
            DB::beginTransaction();
            $result = $this->artisan_repo->unbanArtisan($artisan);
            if ($result) {
                DB::commit();
                return response()->json(['result' => true, 'title' => __('variable.great'), 'message' => "Artisan unbanned successfully"], 200)->setCallback(request()->input('callback'));
            } else {
                DB::rollBack();
                Log::error(__('log.reject_artisan_error'), [$id]);
                return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'unban artisan'])], 200)->setCallback(request()->input('callback'));
            }
        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error while banning artisan', [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'unban artisan'])], 200)->setCallback(request()->input('callback'));
        }
    }
    public function fetchdetails($id)
    {
        $id = decrypt_id_info($id);
        $artisan = $this->artisan_repo->getInformation($id);
        if(!$artisan){
            abort(404);
        }
        return $artisan;
    }

}
