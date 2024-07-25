<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Repositories\Payment\PaymentRepository;
use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Payment\Payment;
use App\Models\Artisan\Artisan;
use Exception;
use Sentinel;
use Log;
use DB;

class PaymentController extends Controller
{
    
    protected $payment_repo;
    
    public function __construct(PaymentRepository $payment_repo)
    {
        $this->payment_repo = $payment_repo;
    }
    
    public function index()
    {
        $records = $this->getPaginationRecordCount(request());
        $search = empty(request()->search) ?  '' : request()->search;
        $status = empty(request()->status) ? 'active' : request()->status;
        $payments = $this->payment_repo->search($search);
        if($status == 'deleted'){
            $payments = $payments->onlyTrashed();
        }
        $payments = $payments->paginate($records);
        return view('modules.payment.index',compact('payments','search','status','records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $artisans = Artisan::select('id','first_name','last_name')->get();
        return view('modules.payment.create', compact('artisans'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PaymentRequest $request)
    {
        try{
            DB::beginTransaction();
            $request->request->add([
                'user_id' => Sentinel::getUser()->id,
                'artisan_id' => decrypt_id_info($request->artisan_id)
            ]);
            $payment = $this->payment_repo->createPayment($request->all());
            DB::commit();
            return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.add_payment')],200)->setCallback(request()->input('callback'));
        }catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.add_payment_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create payment'])],200)->setCallback(request()->input('callback'));
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
        $id = decrypt_id_info($id);
        $payment = $this->payment_repo->getInformation($id);
        if(!$payment){
            abort(404);
        }
        return view('modules.payment.show', compact('payment'));
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
        $artisans = Artisan::select('id', 'first_name', 'last_name')->get();
        $payment = Payment::findOrFail($id);
        return view('modules.payment.edit',compact('artisans','payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PaymentRequest $request, $id)
    {
        try {
            $id = decrypt_id_info($id);
            $payment = Payment::find($id);
            if(!$payment){
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('responses.payment_not_found')],200)->setCallback(request()->input('callback'));
            }
            DB::beginTransaction();
            $request->request->add([
                'user_id' => Sentinel::getUser()->id,
                'artisan_id' => decrypt_id_info($request->artisan_id)
            ]);
            $response = $this->payment_repo->updatePayment($payment,$request->all());
            if($response == false){
                DB::rollBack();
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'update payment'])],200)->setCallback(request()->input('callback'));
            }
            DB::commit();
            return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.update_payment')],200)->setCallback(request()->input('callback'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Payment Update Error", [$e->getMessage()]);
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('error.500', ['operation' => 'update payment'])], 200)->setCallback(request()->input('callback'));
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
        $payment = Payment::find($id);
        if (!$payment) {
            return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.payment_not_found')], 200)->setCallback(request()->input('callback'));
        }
        try{
            $response = $this->payment_repo->deletePayment($payment, Sentinel::getUser()->id);
            if($response){
                DB::commit();
                return response()->json(['result'=>true,'title'=>__('variable.great'),'message'=>__('responses.delete_payment')],200)->setCallback(request()->input('callback'));
            }
            else{
                DB::rollBack();
                Log::error(__('log.delete_payment_error'),[$id]);
                return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'delete payment'])],200)->setCallback(request()->input('callback'));
            }
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error(__('log.delete_payment_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'delete payment'])],200)->setCallback(request()->input('callback'));
        }
    }
}
