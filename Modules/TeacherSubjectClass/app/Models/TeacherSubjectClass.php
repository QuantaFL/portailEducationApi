<?php

namespace Modules\TeacherSubjectClass\app\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Classes\Models\Classes;
use Modules\Subject\Models\Subject;
use Modules\Teacher\Models\Teacher;


class TeacherSubjectClass extends Model
{
    use HasFactory;

    protected $table = 'teacher_subject_class';
    protected $with = ['teacher', 'subject', 'class'];

    /**
     * The attributes that are mass assignable.
     */
  protected $guarded = [];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class);
    }
}
