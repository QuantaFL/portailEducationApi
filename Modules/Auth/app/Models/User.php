<?php

namespace Modules\Auth\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\Factories\UserFactory;
use Modules\Etudiant\Models\Etudiant;
use Tymon\JWTAuth\Contracts\JWTSubject;

 class User extends Authenticatable implements JWTSubject
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

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'first_name'          => $this->first_name,
            'last_name'             => $this->last_name,
            'email'           => $this->email,
            'phone'       => $this->phone,
            'role_id'         => $this->role_id,
            'status'          => $this->status,
        ];
    }
     public function etudiant()
     {
         return $this->hasOne(Etudiant::class);
     }
     public function role()
     {
         return $this->belongsTo(Role::class);
     }
}
