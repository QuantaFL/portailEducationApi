<?php

namespace Modules\AnneAcademique\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnneeAcademiqueRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'annee_debut' => ['required', 'integer', 'digits:4', 'lte:annee_fin'],
            'annee_fin' => ['required', 'integer', 'digits:4', 'gte:annee_debut'],
            'est_courante' => ['nullable', 'boolean'],
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
            'annee_debut.required' => 'L’année de début est obligatoire.',
            'annee_fin.required' => 'L’année de fin est obligatoire.',
            'annee_debut.lte' => 'L’année de début doit être inférieure ou égale à l’année de fin.',
            'annee_fin.gte' => 'L’année de fin doit être supérieure ou égale à l’année de début.',
            'est_courante.boolean' => 'Le champ "est courante" doit être vrai ou faux.',
        ];
    }
}
