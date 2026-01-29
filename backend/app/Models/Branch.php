<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'manager_name',
        'capacity',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function trainingSessions()
    {
        return $this->hasMany(TrainingSession::class);
    }

    public function coaches()
    {
        return $this->hasMany(Coach::class);
    }

    public function monthlyRecords()
    {
        return $this->hasMany(MonthlyRecord::class);
    }
}
