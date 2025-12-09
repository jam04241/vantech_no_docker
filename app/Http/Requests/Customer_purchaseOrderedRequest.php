<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class Customer_purchaseOrderedRequest extends FormRequest
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
            'dr_receipt_id' => 'required|exists:dr_transactions,id',
            'product_id' => 'required|exists:products,id',
            'customer_id' => 'required|exists:customers,id',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'total_price' => 'required|numeric|min:0',
            'order_date' => 'required|date',
            'status' => 'required|in:Success',
        ];
    }
}
