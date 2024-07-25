<?php

namespace Amounee\Http\Requests\v1\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterIndividualRequest extends FormRequest
{
    /**
     * TO DO :: May require update to have the request verified here as well.
     * 
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     * 
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
            'email' => 'required|email|unique:users,email|max:191',
            'phone_number' => 'required|min:6|max:12|unique:users,phone_number',
            'password' => 'required|min:8|max:150|confirmed',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'result' => false,
            'message' => collect($validator->errors())->flatten()->first(),
            'all_failed_validations' => collect($validator->errors())->flatten()
        ], 422));
    }
}
