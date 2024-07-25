<?php

namespace Amounee\Http\Controllers\v1\Artisan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use App\Exceptions\Auth\BannedException;
use App\Repositories\Artisan\ArtisanRepository;
use App\Models\Artisan\Artisan;
use Amounee\Http\Requests\v1\Artisan\UpdatePersonalDetailsRequest;
use Amounee\Http\Requests\v1\Artisan\UpdateAwardRequest;
use Amounee\Http\Requests\v1\Artisan\UpdateAddressDetailsRequest;
use Amounee\Http\Requests\v1\Artisan\UpdateProfileImageRequest;
use Amounee\Http\Requests\v1\Artisan\UpdateArtisanCardAndIdProofRequest;
use Exception;
use Sentinel;
use Log;
use DB;
use App\Transformers\ArtisanTransformer;

class ArtisanController extends Controller
{
    protected $artisan_repo;
    public function __construct(ArtisanRepository $artisan_repo)
    {
        $this->artisan_repo=$artisan_repo;
    }
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
       
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $id = decrypt_id_info($id);
            $artisan_id=Sentinel::getUser()->artisan->id;
            if($id!=$artisan_id)
            {
                return $this->responseFail('Error while viewing profile');
            }
            $artisan=$this->artisan_repo->getInformation($id);
            if(!$artisan){
                return $this->responseFail('Error while viewing profile');
            }
            $artisan_card=$artisan->artisanFiles()->whereIn('file_type', ['artisan_card'])->first();
            $artisan_files=$artisan->artisanFiles()->whereIn('file_type', ['id_proof'])->get();
            $passbook_picture=$artisan->artisanFiles()->whereIn('file_type', ['passbook_picture'])->first();
            foreach($artisan_files as $id_proof)
            {
                $id_proofs[]=$id_proof->fileInfo->display_url;
            }
            return $this->responseSuccessWithData([
                'profile_image' => empty($artisan->photo())?"N/A":$artisan->photo->display_thumbnail_url,
                'first_name' => $artisan->first_name,
                'last_name'  => $artisan->last_name,
                'trade_name' => $artisan->trade_name,
                'gst'=> $artisan->gst ? $artisan->gst: "N/A",
                'phone_number' => $artisan->phone_number,
                'email' => $artisan->email,
                'craft_practiced' => $artisan->category->category_name,
                'street1' => $artisan->street1,
                'street2' => $artisan->street2,
                'zip' => $artisan->zip,
                'city' => $artisan->city,
                'state' => $artisan->state,
                'country' => $artisan->country,
                'account_name' => $artisan->account_name,
                'account_number' => $artisan->account_number,
                'bank_name' => $artisan->bank_name,
                'ifsc' => $artisan->ifsc,
                'awards' => $artisan->awards ? $artisan->awards : "N/A",
                'artisan_card' => $artisan_card?$artisan_card->fileInfo->display_url: "N/A",
                'id_proof' => $id_proofs,
                'passbook_picture' => $passbook_picture->fileInfo->display_url
            ]);
        }
        catch(Exception $e){
            Log::error('Error while viewing profile',[$e->getMessage()]);
            return $this->responseFail('Error while viewing profile');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
       
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
    }

    //update perspnal details
    public function updatePersonalDetails(UpdatePersonalDetailsRequest $request)
    {
        try
        {
            // $id = decrypt_id_info($id);
            $id=Sentinel::getUser()->artisan->id;
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_not_found'));
            }
            $attributes = $request->all();
            $attributes['category_id'] = decrypt_id_info($request->category_id);
            $attributes['country_code']="+91";
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisanPersonalDetails($artisan, $attributes);
            if ($response == false) {
                DB::rollBack();
                return $this->responseFail(__('api/responses.update_artisan_personal_detail_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.update_artisan_personal_detail_success')]);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('Error while Updating Personal Details => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.update_artisan_personal_detail_failure'));
        }
    }

    //update address details
    public function updateAddressDetails(UpdateAddressDetailsRequest $request)
    {
        try
        {
            // $id = decrypt_id_info($id);
            $id=Sentinel::getUser()->artisan->id;
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_not_found'));
            }
            $attributes = $request->all();
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisanAddressDetails($artisan, $attributes);
            if ($response == false) {
                DB::rollBack();
                return $this->responseFail(__('api/responses.update_artisan_address_detail_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.update_artisan_address_detail_success')]);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('Error while Updating Address Details => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.update_artisan_address_detail_failure'));
        }
    }

    //update award details
    public function updateAwardDetails(UpdateAwardRequest $request)
    {
        try
        {
            // $id = decrypt_id_info($id);
            $id=Sentinel::getUser()->artisan->id;
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_not_found'));
            }
            $attributes = $request->all();
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisanAwardDetails($artisan, $attributes);
            if ($response == false) {
                DB::rollBack();
                return $this->responseFail(__('api/responses.update_artisan_award_detail_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.update_artisan_award_detail_success')]);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('Error while Updating Award Details => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.update_artisan_award_detail_failure'));
        }
    }

    //update profile image (vendor-picture)
    public function updateProfileImage(UpdateProfileImageRequest $request)
    {
        try
        {
            $id=Sentinel::getUser()->artisan->id;
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_not_found'));
            }
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisanProfileImage($artisan, $request);
            if (!$response) {
                DB::rollBack();
                return $this->responseFail(__('api/responses.update_artisan_profile_image_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.update_artisan_profile_image_success')]);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('Error while Updating Profile Image => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.update_artisan_profile_image_failure'));
        }
    }

    //update artisan card and id proof
    public function updateArtisanCardIdProof(UpdateArtisanCardAndIdProofRequest $request)
    {
        try
        {
            $id=Sentinel::getUser()->artisan->id;
            $artisan = Artisan::find($id);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_not_found'));
            }
            DB::beginTransaction();
            $response = $this->artisan_repo->updateArtisanIdProofAndArtisanCard($artisan, $request);
            if (!$response) {
                DB::rollBack();
                return $this->responseFail(__('api/responses.update_artisan_artisan_card_id_proof_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.update_artisan_artisan_card_id_proof_success')]);
        }
        catch(Exception $e){
            DB::rollBack();
            Log::error('Error while Updating Artisan Card and Id Proof  => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.update_artisan_artisan_card_id_proof_failure'));
        }
    }
}
