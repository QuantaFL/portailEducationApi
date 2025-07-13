<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'nullable|email|unique:users,email',
            'phone' => 'nullable|string|unique:users,phone',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string',
            'address' => 'nullable|string',
            'role_id' => 'required|exists:roles,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    public function messages(): array
    {
        return [
            'first_name.required' => 'The first name is required.',
            'last_name.required' => 'The last name is required.',
            'email.email' => 'The email field must be a valid email address.',
            'email.unique' => 'This email is already in use.',
            'phone.unique' => 'This phone number is already in use.',
            'date_of_birth.date' => 'The date of birth must be a valid date.',
            'gender.string' => 'The gender must be a string.',
            'address.string' => 'The address must be a string.',
            'role_id.required' => 'The role is required.',
            'role_id.exists' => 'The selected role is invalid.',
        ];
    }
}
