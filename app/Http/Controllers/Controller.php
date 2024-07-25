<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use App\Exceptions\Auth\BannedException;
use DateTime;
use Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function checkIfNumericArray($limit){
        $array = explode(',',$limit);
        foreach($array as $key => $value){
            if(!is_numeric($value)){
                return false;
            }
        }
        return true;
    }

    public function getPaginationRecordCount($request){

        $records = config('constant.default_pagination_records');

        if($request->has('records')){

            if($this->checkIfNumericArray($request->input('records'))){

                if(in_array($request->input('records'), config('constant.LIMIT_ARRAY'))){

                    $records = $request->input('records');
                }
            }
        }
        return $records;
    }

    public function getPaginationRecordCountByKey($request,$key){

        $records = config('constant.default_pagination_records');

        if($request->has($key)){

            if($this->checkIfNumericArray($request->input($key))){

                if(in_array($request->input($key), config('constant.LIMIT_ARRAY'))){

                    $records = $request->input($key);
                }
            }
        }
        return $records;
    }

    public function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public function returnResponse(array $parameters, int $statusCode) {
        return response()->json($parameters, $statusCode)->setCallback(request()->input('callback'));
    }

    public function responseSuccess($message) {
        return $this->returnResponse([
            'result'    => true,
            'message'   => is_null($message) ? 'N/A' : $message
        ], 200);
    }

    public function responseSuccessWithData($additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => true
        ], $additionalData), 200);
    }

    public function responseNotFound($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 404);
    }

    public function responseFail($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 200);
    }

    public function responseFailValidation($message, $additionalData = []) {
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), 422);
    }

    public function responseInternalServerError($message, $additionalData = [], $statusCode = 501){
        return $this->returnResponse(array_merge([
            'result'    => false,
            'message'   => $message
        ], $additionalData), $statusCode);
    }

    protected function getErrorResponse($e,$slug = 'operation')
    {
        $response_to_user = ['result' => false];
        $err_code = 500;
        if($e instanceof ThrottlingException){
            $response_to_user['message'] = __('auth/backend/responses.throttle',['seconds'=>$e->getDelay()]);
            $err_code = 401;
        }
        else if($e instanceof BannedException){
            $response_to_user['message'] = $e->getMessage();
            $err_code = 401;
        }
        else if($e instanceof JWTException){
            $response_to_user['message'] = $e->getMessage();
            $err_code = 400;
            Log::error(__('log.response_error',['slug' => $slug]),[$e->getMessage()]);
        }
        else{
            $response_to_user['message'] = __('error.500',['operation'=>$slug]);
            Log::error(__('log.response_error',['slug' => $slug]),[$e->getMessage(),$e->getLine(), $e->getTraceAsString()]);
        }
        return response()->json($response_to_user, $err_code)->setCallback(request()->input('callback'));
    }
}
