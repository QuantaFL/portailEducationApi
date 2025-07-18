<?php

namespace Modules\ParentModule\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;
use Modules\Etudiant\Models\Etudiant;

class Parents extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Etudiant::class, 'student_id');
    }
}
