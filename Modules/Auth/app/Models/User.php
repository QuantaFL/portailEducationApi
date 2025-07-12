<?php

namespace Modules\Auth\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Database\Factories\UserFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;

abstract class User extends Authenticatable implements JWTSubject
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
            'nom_utilisateur' => $this->nom_utilisateur,
            'prenom'          => $this->prenom,
            'nom'             => $this->nom,
            'email'           => $this->email,
            'telephone'       => $this->telephone,
            'role_id'         => $this->role_id,
            'statut'          => $this->statut,
        ];
    }
}
