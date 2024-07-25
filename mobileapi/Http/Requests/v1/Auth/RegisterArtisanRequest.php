<?php

namespace Amounee\Http\Requests\v1\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegisterArtisanRequest extends FormRequest
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
        $rules = [
            'profile_photo' => 'sometimes|mimes:jpg,jpeg,png|max:2048',
            'first_name' => 'required|max:191',
            'last_name' => 'required|max:191',
            'email' => 'required|email|unique:artisans,email|max:191',
            'country_code' => 'min:0|max:4',
            'phone_number' => 'required|min:10|max:12|unique:artisans,phone_number',
            'trade_name' => 'required|max:191',
            'gst' => 'nullable|max:191',
            'category_id' => 'required',
            'street1' => 'required|max:191',
            'street2' => 'required|max:191',
            'zip' => 'required|max:191',
            'city' => 'required|max:191',
            'state' => 'required|max:191',
            'country' => 'required|max:191',
            'account_name' => 'required|max:191',
            'account_number' => 'required|max:191',
            'bank_name' => 'required|max:191',
            'ifsc' => 'required|max:191',
            'awards' => 'nullable|max:191',
            'id_proof.*' => 'required|mimes:jpg,jpeg,png|max:2048',
            'artisan_cards' => 'sometimes|mimes:jpg,jpeg,png|max:2048',
            'passbook_picture' => 'required|mimes:jpg,jpeg,png|max:2048'
        ];
        if ($this->method() == 'POST' && !isset($this->artisan)) {
            return $rules;
        } else {
            $rules['email'] = 'required|max:191|email|unique:artisans,email,'.decrypt_id_info($this->artisan);
            $rules['phone_number'] = 'required|min:10|max:12|unique:artisans,phone_number,' . decrypt_id_info($this->artisan);
            $rules['id_proof.*'] =   'nullable|mimes:jpg,jpeg,png|max:2048';
            $rules['artisan_cards'] =   'nullable|mimes:jpg,jpeg,png|max:2048';
            $rules['passbook_picture'] =   'nullable|mimes:jpg,jpeg,png|max:2048';
            return $rules;
        }
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
