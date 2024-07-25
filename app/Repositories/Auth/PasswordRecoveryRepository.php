<?php
namespace App\Repositories\Auth;

use Carbon\Carbon;
use Sentinel;
use AuthRepository;
use Reminder;
use Exception;
use DB;
use Log;
use App\Repositories\EmailRepository;

class PasswordRecoveryRepository {
    public function initiateForgotPasswordRequest($email){
        $response_to_user = ['result' => false];
        $user = Sentinel::findByCredentials(['email' => $email]);
        if($user){
            $response_to_user = $this->createForgotPasswordReminderAndSendEmail($user);
        }
        else{
            $response_to_user['title'] = __('variable.sorry');
            $response_to_user['message'] = __('auth/backend/responses.invalid_email');
        }
        return $response_to_user;
    }
    public function createForgotPasswordReminderAndSendEmail($user){
        $response_to_user = ['result' => false];
        if(Reminder::exists($user)){
            $response_to_user['title'] = __('variable.attention');
            $response_to_user['message'] = __('auth/backend/responses.failed_reset_pswd');
        }
        else{
            try{
                DB::beginTransaction();
                $reminder = Reminder::create($user);

                $name = $user->name();

                $site_url = route('password.reset',[$user->id_token, $reminder->code]);
                
                EmailRepository::forgotPasswordEmail($user->email, ['name' => $name, 'site_url' => $site_url]);
                
                DB::commit();
                $response_to_user = ['result' => true, 'message' => __('auth/backend/responses.success_reset_pswd_mail'), 'title' => __('variable.great')];
            }
            catch(Exception $e){
                DB::rollBack();
                Log::error(__('auth/backend/log.password_reminder_error'),[$e->getMessage()]);
                $response_to_user['title'] = __('variable.attention');
                $response_to_user['message'] = __('auth/backend/responses.failed_password_reminder');
            }
        }
        return $response_to_user;
    }
    public function initateResetPasswordRequest($id, $code, $password){
        $response_to_user = ['result' => false];
        $user = Sentinel::findById($id);
        if(!$user){
            $response_to_user['title'] = __('variable.attention');
            $response_to_user['message'] = __('auth/backend/responses.expire_reset_pswd_request');
        }
        else{
            $response_to_user = $this->completeResetPasswordRequest($user, $code, $password);
        }
        return $response_to_user;
    }

    public function completeResetPasswordRequest($user, $code, $password){
        $response_to_user = ['result' => false];
        $reminder = Reminder::exists($user,$code);
        if($reminder){
            try{
                Reminder::complete($user, $code, $password);

                EmailRepository::passwordChanged($user->email, ['name' => $user->name()]);
                
                $response_to_user['result'] = true;
                $response_to_user['title'] = __('variable.great');
                $response_to_user['message'] = __('auth/backend/responses.success_reset_pswd');
            }
            catch(Exception $e){
                Log::error(__('auth/backend/log.change_pswd_error'),[$e->getMessage()]);
                $response_to_user['title'] = __('variable.attention');
                $response_to_user['message'] = __('auth/backend/responses.failed_password_reminder');
            }
        }
        else{
            $response_to_user['title'] = __('variable.attention');
            $response_to_user['message']= __('auth/backend/responses.expire_reset_pswd_request');
        }
        return $response_to_user;
    }
}
