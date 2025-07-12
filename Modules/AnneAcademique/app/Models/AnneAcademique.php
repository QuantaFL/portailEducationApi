<?php

namespace Modules\AnneAcademique\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Modules\AnneAcademique\Database\Factories\AnneAcademiqueFactory;

class AnneAcademique extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   // protected $fillable = [];
    protected $guarded = [];

    // protected static function newFactory(): AnneAcademiqueFactory
    // {
    //     // return AnneAcademiqueFactory::new();
    // }
}
