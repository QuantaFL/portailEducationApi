<?php

namespace Modules\Parent\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;
use Modules\Etudiant\Models\Etudiant;

class Parent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'student_id',
        'phone_number',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function student()
    {
        return $this->belongsTo(Etudiant::class, 'student_id');
    }
}
