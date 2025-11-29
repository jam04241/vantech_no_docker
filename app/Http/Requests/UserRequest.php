<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username')->ignore($userId)
            ],
            'password' => $this->isMethod('post') ? 'required|string|min:8|confirmed' : 'nullable|string|min:8|confirmed',
            'role' => 'required|string|in:admin,manager,staff,cashier',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages()
    {
        return [
            'first_name.required' => 'First name is required.',
            'first_name.string' => 'First name must be a valid text.',
            'first_name.max' => 'First name cannot exceed 255 characters.',

            'middle_name.string' => 'Middle name must be a valid text.',
            'middle_name.max' => 'Middle name cannot exceed 255 characters.',

            'last_name.required' => 'Last name is required.',
            'last_name.string' => 'Last name must be a valid text.',
            'last_name.max' => 'Last name cannot exceed 255 characters.',

            'username.required' => 'Username is required.',
            'username.string' => 'Username must be a valid text.',
            'username.max' => 'Username cannot exceed 255 characters.',
            'username.unique' => 'This username is already taken.',

            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a valid text.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'role.required' => 'Role is required.',
            'role.string' => 'Role must be a valid text.',
            'role.in' => 'Role must be one of: admin, manager, staff, or cashier.',
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes()
    {
        return [
            'first_name' => 'first name',
            'middle_name' => 'middle name',
            'last_name' => 'last name',
            'username' => 'username',
            'password' => 'password',
            'role' => 'role',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        // Trim whitespace from text fields
        $this->merge([
            'first_name' => trim($this->first_name ?? ''),
            'middle_name' => trim($this->middle_name ?? ''),
            'last_name' => trim($this->last_name ?? ''),
            'username' => trim($this->username ?? ''),
        ]);
    }
}
