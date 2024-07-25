<?php

namespace App\Http\Requests\Artisan;

use Illuminate\Foundation\Http\FormRequest;

class RejectionNoteRequest extends FormRequest
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
            'rejection_note' => 'required|max:191'
        ];
    }
}
