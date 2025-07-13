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
            'prenom' => 'required|string',
            'nom' => 'required|string',
         //   'nom_utilisateur' => 'required|string|unique:users,nom_utilisateur',
          //  'mot_de_passe' => 'required|string|min:8',
            'email' => 'nullable|email|unique:users,email',
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
            'prenom.required' => 'Le prénom est obligatoire.',
            'nom.required' => 'Le nom est obligatoire.',
           // 'nom_utilisateur.required' => 'Le nom d’utilisateur est obligatoire.',
           // 'nom_utilisateur.unique' => 'Ce nom d’utilisateur est déjà utilisé.',
            //'mot_de_passe.required' => 'Le mot de passe est obligatoire.',
            //'mot_de_passe.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'email.email' => 'Le champ email doit être une adresse email valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'role_id.required' => 'Le rôle est obligatoire.',
            'role_id.exists' => 'Le rôle sélectionné est invalide.',
        ];
    }
}
