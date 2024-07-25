<?php

namespace Amounee\Http\Controllers\v1\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use App\Exceptions\Auth\BannedException;
use Amounee\Http\Requests\v1\Auth\LoginRequest;
use Amounee\Http\Requests\v1\Auth\RegisterIndividualRequest;
use Amounee\Http\Requests\v1\Auth\RegisterArtisanRequest;
use Amounee\Http\Requests\v1\Auth\OtpRequest;
use Amounee\Http\Requests\v1\Auth\ResendOtpRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use Amounee\Http\Requests\v1\Auth\AppDeviceRegistrationRequest;
use App\Repositories\UserRepository;
use App\Repositories\Artisan\ArtisanRepository;
// use App\Repositories\Individual\IndividualRepository;
use App\Repositories\Auth\PasswordRecoveryRepository;
use App\Models\Auth\SentinelUser;
use App\Models\Auth\Device;

use Sentinel;
use Exception;
use Log;
use DB;
use App\Adapter\Notification\SmsApi;

class AuthController extends Controller
{

    protected $individual_repo;
    protected $artisan_repo;
    public function __construct(ArtisanRepository $artisan_repo)
    {
            $this->artisan_repo=$artisan_repo;
    }

    //API ID :: API-AM-01
    //send otp(login)
    public function login(LoginRequest $request)
    {
        try
        {
            $phone_number=$request->phone_number;
            $user=SentinelUser::where('phone_number',$phone_number)->first();
            if(!$user)
            {
                return $this->responseNotFound(__('auth/backend/responses.error_invalid_cred'));
            }
            if(!$user->inRole('artisan')) 
            {
                return $this->responseNotFound(__('auth/backend/responses.unauthorized_access'));
            }
            $otp=rand(1000,9999);
            $user->otp=$otp;
            $user->save();
            //Otp sms api implementation
            // $sms = new SmsApi();
            // $msg = __('sms.otp',['otp'=>$otp]);
            // Log::info($msg);
            // $sms->sendSms([$user->phone_number],$msg);
            $token=JWTAuth::fromUser($user);
            if (!$token) {
                return $this->responseNotFound(__('auth/backend/responses.error_invalid_cred'));
            }
            $device = Device::updateOrCreate(
                ['device_id' => trim($request->input('device_id'))],
                ['device_type' => $request->input('device_type'), 'reg_id' => $request->input('reg_id'), 'user_id' => $user->id]
                );
            return $this->responseSuccessWithData(['token' => $token]);
        }
        catch(Exception $e){
            return $this->getErrorResponse($e,'login');
        }
        
    }

    // API ID ::API-AM-06 
    //register artisan
    public function registerArtisan(RegisterArtisanRequest $request)
    {
        try
        {
            $attributes = $request->all();
            $attributes['category_id'] = decrypt_id_info($request->category_id);
            $attributes['country_code']=empty($request->country_code)?"+91":$request->country_code;
            DB::beginTransaction();
            $artisan=$this->artisan_repo->createArtisan($attributes,null,0);
            if (!$artisan) {
                return $this->responseFail(__('api/responses.artisan_registration_failure'));
            }
             //create artisan status
           $status= $this->artisan_repo->createArtisanStatus($artisan,'pending', 0);
           if (!$status) {
            return $this->responseFail(__('api/responses.artisan_registration_failure'));
            }
            $result = $this->artisan_repo->createArtisanFiles($artisan, $request);
            if (!$result) {
                return $this->responseFail(__('api/responses.artisan_registration_failure'));
            }
            DB::commit();
            return $this->responseSuccessWithData(['message' => __('api/responses.success_artisan_registration',['full_name' => $artisan->user->first_name]), 'name' => $artisan->user->first_name]);
        }
        catch(Exception $e) {
            DB::rollBack();
            Log::error('Error while creating an Artisan => ',[$e->getMessage()]);
            return $this->responseFail(__('api/responses.artisan_registration_failure'));
        }
    }

    //API ID :: API-AM-03
    //verify otp
    public function verifyOtp(OtpRequest $request){
        try{
            $token = JWTAuth::parseToken()->authenticate();
            if(!$token)
            {
                return $this->responseFail("Artisan Not Found");
            }
            $user = request()->user();
            $status=$user->artisan->status;
            // if($request->input('otp') == $user->otp)
            if($request->input('otp') == '0000')
            {
                if($status=='rejected')
                {
                    return $this->responseSuccessWithData(['message' => __('api/responses.otp_verification_success'),'status'=>$status,'rejection_note'=>$user->artisan->rejection_note]);
                }
                else
                {
                    return $this->responseSuccessWithData(['message' => __('api/responses.otp_verification_success'),'status'=>$status]);
                }
               
            }
            else{
                return $this->responseFail(__('api/responses.otp_verification_failure'));
            }

        }
        catch(Exception $e){
            return $this->getErrorResponse($e, "OTP Verification");
        }
    }

    //API ID:API-AM-02
    //Resend otp
    public function resendOtp(ResendOtpRequest $request)
    {
        $token = JWTAuth::parseToken()->authenticate();
        if(!$token)
        {
            return $this->responseFail("Artisan Not Found");
        }
        if(!$request->headers->has('Authorization')){
            return $this->responseFailValidation(__('error.missing_token'),['title'=>__('variable.attention')]);
        }
        try{
           $user=request()->user();
           $otp=rand(1000,9999);
           $user->otp=$otp;
           $user->save();
           return $this->responseSuccessWithData(['message' => __('api/responses.resend_otp_success')]);

        } catch(Exception $e){
            Log::error('log.logout_error',[$e->getMessage()]);
            return $this->responseFail(__('error.500',['operation'=>'logout']),['title'=>__('variable.attention')]);
        }
    }

    // API ID ::  API-T-6
    // Logout
    // Murtuza
    
    public function logout(Request $request)
    {
        if(!$request->headers->has('Authorization')){
            return $this->responseFailValidation(__('error.missing_token'),['title'=>__('variable.attention')]);
        }
        try{
            $token = JWTAuth::parseToken();
            if($token->authenticate()){
                $response = $token->invalidate();
            }
            return $this->responseSuccessWithData(['message' => __('auth/backend/responses.success_logout'), 'title'=>__('variable.great')]);
        } catch(Exception $e){
            if($e instanceof JWTException){
                return $this->responseSuccessWithData(['message' => __('auth/backend/responses.success_logout'), 'title'=>__('variable.great')]);
            }
            Log::error('log.logout_error',[$e->getMessage()]);
            return $this->responseFail(__('error.500',['operation'=>'logout']),['title'=>__('variable.attention')]);
        }
    }

    //Register Device API
    public function registerDevice(AppDeviceRegistrationRequest $request)
    {
        try {
            $device = Device::updateOrCreate(
                ['device_id' => trim($request->input('device_id'))],
                ['device_type' => $request->input('device_type'), 'reg_id' => $request->input('reg_id'), 'user_id' => Sentinel::getUser()->id]
            );
            return $this->responseSuccessWithData(['message' => __('api/responses.device_registered_success'), 'title'=>__('variable.great')]);
        } catch (Exception $e) {
            return $this->responseFail(__('error.500',['operation'=>'register device']),['title'=>__('variable.attention')]);
        }
    }
}
