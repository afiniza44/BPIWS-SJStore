<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $fillable = ['username', 'password', 'role'];

    protected $hidden = ['password'];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function suratJalan()
    {
        return $this->hasMany(SuratJalan::class);
    }
}
