<?php

namespace Modules\Classes\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassesRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'nom_classe' => 'required|string|unique:classes,nom_classe',
            'annee_academique_id' => 'required|exists:anne_academiques,id',
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
