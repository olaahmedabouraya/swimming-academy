<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'date_of_birth',
        'address',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    public function player()
    {
        return $this->hasOne(Player::class);
    }

    public function isPlayer()
    {
        return $this->role === 'player';
    }

    public function isManager()
    {
        return $this->role === 'manager' || $this->role === 'admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}



