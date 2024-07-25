<?php

namespace App\Imports;

use App\Models\Artisan\Artisan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Auth\SentinelUser;
use App\Repositories\Artisan\ArtisanRepository;
use App\Models\Category\Category;
use Exception;
use DB;
use Validator;
class ArtisansImport implements ToCollection,WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $artisan_repo;
    public function __construct(ArtisanRepository $artisan_repo)
    {
        $this->artisan_repo=$artisan_repo;
    }
    public function collection(Collection $rows)
    {
        try
        {
            foreach($rows as $row)
            {
                if (!isset($row['first_name']) || !isset($row['last_name']) || !isset($row['email']) || !isset($row['trade_name']) 
                || !isset($row['category_name']) || !isset($row['street1']) || !isset($row['street2']) || !isset($row['zip'])
                || !isset($row['city']) || !isset($row['state']) || !isset($row['country']) || !isset($row['account_name'])   
                || !isset($row['account_number']) || !isset($row['bank_name']) || !isset($row['ifsc']) || !isset($row['account_name']) 
                || !isset($row['country_code']) || !isset($row['phone_number']) || !isset($row['status'])
                ) 
                {
                    throw new \Exception("file headers is not proper", 422);
                }
                $validator = Validator::make([
                    'first_name'      => $row['first_name'],
                    'last_name'      => $row['last_name'],
                    'country_code'   => $row['country_code'],
                    'phone_number'   => $row['phone_number'],
                    'trade_name'    =>  $row['trade_name'],
                    'email'        => $row['email'],
                    'street1'       => $row['street1'],
                    'street2'       =>$row['street2'],
                    'zip'           =>$row['zip'],
                    'city'          =>$row['city'],
                    'state'         =>$row['state'],
                    'country'       =>$row['country'],
                    'account_name'  =>$row['account_name'],
                    'account_number' =>$row['account_number'],
                    'bank_name'     =>$row['bank_name'],
                    'ifsc'          =>$row['ifsc']

                ],[
                    'first_name' => ['required|max:191'],
                    'last_name' => ['required|max:191'],
                    'country_code' => ['required|min:0|max:4'],
                    'phone_number' => ['required|min:8|max:12|unique:artisans,phone_number'],
                    'email' => ['required|email|unique:artisans,email|max:191'],
                    'trade_name' => ['required|max:191'],
                    'street1' => ['required|max:191'],
                    'street2' => ['required|max:191'],
                    'zip' => ['required|max:191'],
                    'city' => ['required|max:191'],
                    'state' => ['required|max:191'],
                    'country' => ['required|max:191'],
                    'account_name' => ['required|max:191'],
                    'account_number' => ['required|max:191'],
                    'bank_name' => ['required|max:191'],
                    'ifsc' => ['required|max:191'],
                ]);
                if ($validator->fails()) {
                    $messages = collect($validator->errors()->all())->implode(',');
                    // $failResults->push(['error' => $messages]);
                    continue;
                }
                $category_name=$row['category_name'];
                $category=Category::where('wocommerce_slug',$category_name)->get()->first();
                $category_id=$category['id'];
                $status=$row['status'];
                $attributes=[
                    'first_name' => $row['first_name'],
                    'last_name' => $row['last_name'],
                    'category_id' =>$category_id,
                    'trade_name' => $row['trade_name'],
                    'gst' => $row['gst'],
                    'country_code' => $row['country_code'],
                    'phone_number' => $row['phone_number'],
                    'email' => $row['email'],
                    'street1' => $row['street1'],
                    'street2' => $row['street2'],
                    'zip' => $row['zip'],
                    'city' => $row['city'],
                    'state' => $row['state'],
                    'country' => $row['country'],
                    'account_name' => $row['account_name'],
                    'account_number' => $row['account_number'],
                    'bank_name' => $row['bank_name'],
                    'ifsc' => $row['ifsc'],
                    'awards' => $row['awards'],
                    'commission'=> null,
                    'status' => $status
                ];
                if($status=='approved')
                {
                    $is_approved=1;
                }
                else if($status=='pending')
                {
                    $is_approved=0;
                }
                DB::beginTransaction();
                $artisan = $this->artisan_repo->createArtisan($attributes, null, $is_approved);
                if (!$artisan) {
                    return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_not_created')], 200)->setCallback(request()->input('callback'));
                }
                //create data in artisan status table
                $status_response=$this->artisan_repo->createArtisanStatus($artisan,$status,$is_approved);
                if (!$status_response) {
                    return response()->json(['result' => false, 'title' => __('variable.sorry'), 'message' => __('responses.artisan_status_not_created')], 200)->setCallback(request()->input('callback'));
                }
                DB::commit();
            }
        }
        catch(Exception $e)
        {
            DB::rollBack();
            Log::error(__('log.add_artisan_error'),[$e->getMessage()]);
            return response()->json(['result'=>false,'title'=>__('variable.sorry'),'message'=>__('error.500',['operation' => 'create artisan'])],200)->setCallback(request()->input('callback'));
        }
    }
}
