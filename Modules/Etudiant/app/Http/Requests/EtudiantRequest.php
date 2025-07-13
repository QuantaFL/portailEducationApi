<?php

namespace Modules\Etudiant\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EtudiantRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'telephone' => ['required', 'string', 'max:20'],
            'role_id' => ['required', 'exists:roles,id'],

            // Champs pour Etudiant
            'date_naissance' => ['required', 'date'],
            'classe_id' => ['required', 'exists:classes,id'],
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
