<?php

namespace App\Http\Requests\Payment;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
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
            'artisan_id' =>'required',
            'payment_amount' =>'required|numeric',
            'payment_type' => 'required',
            'paid_amount' => 'nullable|same:payment_amount',
            'date_payment' => 'nullable|date_format:d/m/Y',
            'note' => 'nullable|max:500'
        ];
    }
}
