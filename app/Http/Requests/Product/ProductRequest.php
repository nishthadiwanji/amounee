<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
        $rules = [
            'product_name' =>'required',
            'sku' =>'nullable',
            'artisan_id' => 'required',
            'stock_status' => 'required',
            'category_id' => 'required',
            'sub_category_id' => 'required',
            'base_price' => 'required|numeric',
            'tax_status' => 'nullable|max:191',
            'tax_class' => 'nullable|max:191',
            'hsn_code' => 'required',
            'commission' => 'nullable',
            'material' => 'nullable',
            'sales_price' => 'numeric',
            'short_desc' => 'required',
            'long_desc' => 'required',
            'product_gallery.*' => 'required|mimes:jpg,jpeg,png|max:2048',
            'product_image' => 'required|mimes:jpg,jpeg,png|max:2048',
        ];
        if ($this->method() == 'POST' && !isset($this->product)) {
            return $rules;
        } else {
            $rules['product_gallery.*'] =   'nullable|mimes:jpg,jpeg,png|max:2048';
            $rules['product_image'] =   'nullable|mimes:jpg,jpeg,png|max:2048';
            // $rules['sku'] = 'required|unique:products,sku,' . decrypt_id_info($this->product);
            return $rules;
        }
    }
}
