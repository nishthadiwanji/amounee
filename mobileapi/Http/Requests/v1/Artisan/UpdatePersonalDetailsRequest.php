<?php

namespace Amounee\Http\Requests\v1\Artisan;

use GuzzleHttp\Psr7\Request;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdatePersonalDetailsRequest extends FormRequest
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
        $artisan=request()->user()->artisan;
        $rules =[
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'trade_name' => 'required|max:191',
            'gst' => 'nullable|max:16',
            'category_id' => 'required'
        ];
        $rules['email'] = 'required|max:191|email|unique:artisans,email,'.$artisan->id;
        $rules['phone_number'] = 'required|min:10|max:12|unique:artisans,phone_number,' .$artisan->id;
        return $rules;
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
