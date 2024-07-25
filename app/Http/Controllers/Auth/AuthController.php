<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Repositories\UserRepository;
use App\Repositories\Auth\PasswordRecoveryRepository;
use App\Exceptions\Auth\BannedException;
use Exception;
use Session;
use Sentinel;
use Log;
use Reminder;

class AuthController extends Controller
{
    public function login(){
        return view('modules.auth.login');
    }
    public function attemptLogin(LoginRequest $request){
        $response_to_user = ['result' => false];
        try{
			$remember_me = $request->has('rememberMe') ? true : false;
			$credentials = [
				'login'    => $request->input('username'),
				'password' => $request->input('password')
            ];
            $user = $remember_me ? Sentinel::authenticateAndRemember($credentials) : Sentinel::authenticate($credentials);
            if($user == false) {
                $response_to_user['title'] = __('variable.attention');
                $response_to_user['message'] = __('auth/backend/responses.invalid_credentials', ['user_name' => $request->input('username')]);
            }
            else {
                if(!$user->isTeamMember()){
                    Sentinel::logout();
                    Session::forget('full_name');
                    $response_to_user['title']=__('variable.attention');
                    $response_to_user['message']=__('auth/backend/responses.unauthorized_access');
                }
                else{
                    $user->setupSystemInfoAfterLogin();
                    $response_to_user['result'] = true;
                    $response_to_user['message'] = __('auth/backend/responses.success_login', ['full_name' => session('full_name')]);
                }
            }
        }
        catch(BannedException $e){
            $response_to_user['title'] = __('variable.banned');
            $response_to_user['message'] = $e->getMessage();
        }
        catch(Exception $e){
            Log::error(__('auth/backend/log.login_error'),[$e->getMessage()]);
            $response_to_user['title'] = __('variable.attention');
            $response_to_user['message'] = $e->getMessage();
        }
        return response()->json($response_to_user, 200)->setCallback($request->input('callback'));
    }
    public function attemptForgotPassword(ForgotPasswordRequest $request){
        $response_to_user = (new PasswordRecoveryRepository())->initiateForgotPasswordRequest($request->email);
        return response()->json($response_to_user, 200)->setCallback(request()->input('callback'));
    }
    public function resetPassword($id,$code){
        $user = Sentinel::findById(decrypt_id_info($id));
        if(!$user){
            return view('errors.unauthorized');
        }
        $reminder = Reminder::exists($user,$code);
        if(!$reminder){
            return view('errors.notice',[
                'message'=>__('auth/backend/responses.expire_reset_pswd_request')
            ]);
        }
        return view('modules.auth.reset-password',compact('id','user','code'));
    }
    public function attemptResetPassword(ResetPasswordRequest $request, $id, $code){
        $response_to_user = (new PasswordRecoveryRepository())->initateResetPasswordRequest(decrypt_id_info($id), $code, $request->password);
        return response()->json($response_to_user, 200)->setCallback($request->input('callback'));
    }
    public function changePassword(){
        return view('modules.auth.change-password');
    }
    public function attemptChangePassword(ChangePasswordRequest $request) {
        $response_to_user = (new UserRepository(Sentinel::getUser()))->changePassword($request->all());
        return response()->json($response_to_user, 200)->setCallback(request()->input('callback'));
    }

    public function generatePassword($id){
        $id = decrypt_id_info($id);
        $user = Sentinel::findById($id);
        $response_to_user = (new UserRepository($user))->generatePassword(request()->all());

        if(Sentinel::inRole('admin')){
            log_activity_by_user('admin_log',Sentinel::getUser(),__('activity_log_messages.password_generated'));
        }

        return response()->json($response_to_user, 200)->setCallback(request()->input('callback'));
    }
    
    public function logout(){
        Sentinel::getUser()->logoutFromSystem();
        return redirect('/login');
    }
}
