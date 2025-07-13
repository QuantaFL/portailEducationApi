<?php

namespace Modules\Etudiant\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;
use Modules\Classes\Models\Classes;

// use Modules\Etudiant\Database\Factories\EtudiantFactory;

class Etudiant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
   // protected $fillable = [];
    protected $guarded = [];
    protected $with = ['user', 'classe'];

    // protected static function newFactory(): EtudiantFactory
    // {
    //     // return EtudiantFactory::new();
    // }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function classe()
    {
        return $this->belongsTo(Classes::class);
    }
}
