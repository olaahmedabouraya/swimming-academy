<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'schedule_id',
        'session_id',
        'attendance_date',
        'status',
        'check_in_time',
        'check_out_time',
        'actual_start_time',
        'actual_end_time',
        'coach_notes',
        'notes',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in_time' => 'datetime:H:i',
        'check_out_time' => 'datetime:H:i',
        'actual_start_time' => 'datetime:H:i',
        'actual_end_time' => 'datetime:H:i',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }
}
