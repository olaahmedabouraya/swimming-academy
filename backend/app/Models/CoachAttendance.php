<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'session_id',
        'attendance_date',
        'scheduled_start_time',
        'scheduled_end_time',
        'actual_start_time',
        'actual_end_time',
        'is_late',
        'late_minutes',
        'notes',
        'hours_worked',
        'recorded_by',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'scheduled_start_time' => 'datetime:H:i',
        'scheduled_end_time' => 'datetime:H:i',
        'actual_start_time' => 'datetime:H:i',
        'actual_end_time' => 'datetime:H:i',
        'is_late' => 'boolean',
        'hours_worked' => 'decimal:2',
    ];

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function session()
    {
        return $this->belongsTo(TrainingSession::class, 'session_id');
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function calculateHoursWorked()
    {
        if ($this->actual_start_time && $this->actual_end_time) {
            $start = \Carbon\Carbon::parse($this->actual_start_time);
            $end = \Carbon\Carbon::parse($this->actual_end_time);
            $hours = $start->diffInMinutes($end) / 60;
            $this->hours_worked = round($hours, 2);
            return $this->hours_worked;
        }
        return 0;
    }
}
