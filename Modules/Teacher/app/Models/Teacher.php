<?php

namespace Modules\Teacher\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\Role;
use Modules\Auth\Models\User;

// use Modules\Teacher\Database\Factories\TeacherFactory;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   // protected $fillable = [];
    protected  $guarded = [];
    protected $with = ['user', 'user.role'];

    // protected static function newFactory(): TeacherFactory
    // {
    //     // return TeacherFactory::new();
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
