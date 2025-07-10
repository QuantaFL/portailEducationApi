<?php

namespace Modules\Auth\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'code_role' => 'required|string|unique:roles,code_role',
            'libelle_role' => 'required|string|unique:roles,libelle_role',
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
            'code_role.required' => 'Le code du rôle est obligatoire.',
            'code_role.unique' => 'Ce code de rôle existe déjà.',
            'libelle_role.required' => 'Le libellé du rôle est obligatoire.',
            'libelle_role.unique' => 'Ce libellé de rôle existe déjà.',
        ];
    }
}
