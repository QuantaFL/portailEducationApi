<?php

namespace Modules\Etudiant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEtudiantRequest extends  FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'classId' => 'required|exists:classes,id',
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'roleId' => 'required|exists:roles,id',
            'address' => 'nullable|string|max:255',
            'dateOfBirth' => 'nullable|date',
            'gender' => 'nullable|string|in:Male,Female,Other',
            'parentUserId' => 'nullable|exists:users,id',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
