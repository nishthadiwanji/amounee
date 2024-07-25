<?php

namespace Amounee\Http\Requests\v1\Artisan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateAddressDetailsRequest extends FormRequest
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
            'street1' => 'required|max:191',
            'street2' => 'required|max:191',
            'zip' => 'required|numeric',
            'city' => 'required|max:191',
            'state' => 'required|max:191',
            'country' => 'required|max:191'
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'result' => false,
            'message' => collect($validator->errors())->flatten()->first(),
            'all_failed_validations' => collect($validator->errors())->flatten()
        ], 422));
    }
}
