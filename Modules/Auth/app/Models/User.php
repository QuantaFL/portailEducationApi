<?php

namespace Modules\Auth\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\Factories\UserFactory;
class User extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   // protected $fillable = [];
    protected $guarded = [];

    protected static function newFactory()
    {
        return \Modules\Auth\Database\Factories\UserFactory::new();
    }
}
