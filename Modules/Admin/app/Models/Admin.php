<?php

namespace Modules\Admin\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Auth\Models\User;

class Admin extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'admin_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
