<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'current_password'=>'required',
            'password' => 'required|min:8|confirmed'
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'current_password.required' => __('auth/backend/validations.required_validation',['field_name' => 'current password']),
            'password.required'  => __('auth/backend/validations.required_validation',['field_name' => 'password']),
            'password.min'  => __('auth/backend/validations.min_length_password'),
            'password.confirmed'  => __('auth/backend/validations.match_password')
        ];
    }
}
