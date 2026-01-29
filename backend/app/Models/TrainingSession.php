<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'group',
        'day_of_week',
        'start_time',
        'end_time',
        'start_date',
        'end_date',
        'is_active',
        'max_capacity',
        'notes',
    ];

    protected $casts = [
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class, 'current_session_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'session_id');
    }

    public function coachAttendances()
    {
        return $this->hasMany(CoachAttendance::class, 'session_id');
    }
}
