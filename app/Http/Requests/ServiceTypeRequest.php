<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ServiceTypeRequest extends FormRequest
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
        $serviceTypeId = $this->route('serviceType')?->id;

        return [
            'name' => 'required|string|max:255|unique:service_types,name' . ($serviceTypeId ? ",$serviceTypeId" : ''),
            'price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'name.required' => 'The service type name is required.',
            'name.string' => 'The service type name must be a string.',
            'name.max' => 'The service type name must not exceed 255 characters.',
            'name.unique' => 'This service type name already exists.',
            'price.required' => 'The service price is required.',
            'price.numeric' => 'The service price must be a number.',
            'price.min' => 'The service price must be at least 0.',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        Log::error('ServiceTypeRequest validation failed', ['errors' => $validator->errors()->all()]);

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors()
        ], 422));
    }
}
