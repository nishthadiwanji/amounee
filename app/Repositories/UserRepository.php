<?php
namespace App\Repositories;

use App\Repositories\EloquentDBRepository;
use App\Models\Auth\SentinelUser as User;
use Ban;
use Sentinel;
// use App\Repositories\EmailRepository;

class UserRepository extends EloquentDBRepository {
    public function __construct(User $user){
        $this->model = $user;
    }
    public function ban(){
        return Ban::ban($this->model);
    }
    public function unban(){
        return Ban::unban($this->model);
    }
    public function updatePassword($new_password) {
    	$credentials = [
    		'password' => $new_password
    	];
    	$update = Sentinel::update($this->model, $credentials);
        //TODO::need to update below code with email
        if($this->model->email != null){
            EmailRepository::passwordChanged($this->model->email, ['name' => $this->model->name()]);
        }
        return true;
    }
    public function isCurrentPasswordValid($current_password){
        $credentials = [
            'phone_number' => $this->model->phone_number,
            'password' => $current_password
        ];
        return Sentinel::validateCredentials($this->model, $credentials);
    }
    public function changePassword($credentials){
        $response_to_user = ['result' => false];
        if($this->isCurrentPasswordValid($credentials['current_password'])){
            $this->updatePassword($credentials['password']);
            $response_to_user['result'] = true;
            $response_to_user['title'] = __('variable.great');
            $response_to_user['message'] = __('auth/backend/responses.changed_pswd');
        }
        else{
            $response_to_user['title'] = __('variable.attention');
            $response_to_user['message'] = __('auth/backend/responses.incorrect_current_pswd');
        }
        return $response_to_user;
    }

    public function generatePassword($credentials){
        $response_to_user = ['result' => false];
        $this->updatePassword($credentials['password']);
        $response_to_user['result'] = true;
        $response_to_user['title'] = __('variable.great');
        $response_to_user['message'] = __('auth/backend/responses.generate_pswd');
        return $response_to_user;
    }

    
}
