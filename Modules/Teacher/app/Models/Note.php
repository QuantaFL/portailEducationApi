<?php

namespace Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Etudiant\Models\Etudiant;
use Modules\Subject\Models\Subject;
use Modules\Teacher\Models\Teacher;
use Modules\Classes\Models\Classes;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'etudiant_id',
        'subject_id',
        'teacher_id',
        'class_id',
        'period',
        'note_exam',
        'note_devoir',
    ];
    protected $with = [
        'subject',
        'etudiant',
        'teacher',
        'class',
    ];

    public function etudiant()
    {
        return $this->belongsTo(Etudiant::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
