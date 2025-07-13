<?php

namespace Modules\Etudiant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;
use Modules\Classes\Models\Classes;

class Etudiant extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $with = ['user', 'classes'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function classes()
    {
        return $this->belongsTo(Classes::class,'class_id');
    }

    public function parent()
    {
        return $this->belongsTo(User::class);
    }
}
