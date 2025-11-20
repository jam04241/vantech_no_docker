<?php

namespace App\Http\Requests;

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
     * @return array<string, mixed>
     */
    public function rules()
    {
         return [
        'product_name' => 'required|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'warranty_period' => 'nullable|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'brand_id' => 'nullable|exists:brands,id',
        'supplier_id' => 'nullable|exists:suppliers,id', // Changed to nullable
        'is_used' => 'boolean'
    ];
    }
}
