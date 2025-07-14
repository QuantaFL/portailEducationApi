<?php

namespace Modules\TeacherSubjectClass\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherSubjectClassRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'teacherId' => 'required|integer|exists:teachers,id',
            'subjectId' => 'required|integer|exists:subjects,id',
            'classId' => 'required|integer|exists:classes,id',
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