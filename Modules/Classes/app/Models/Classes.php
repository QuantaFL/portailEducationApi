<?php

namespace Modules\Classes\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\Classes\Database\Factories\ClassesFactory;

class Classes extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   // protected $fillable = [];
    protected $guarded = [];

    // protected static function newFactory(): ClassesFactory
    // {
    //     // return ClassesFactory::new();
    // }
}
