<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coach extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'specialization',
        'hourly_rate',
        'notes',
        'is_active',
    ];

    protected $casts = [
        'hourly_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function players()
    {
        return $this->hasMany(Player::class);
    }

    public function attendances()
    {
        return $this->hasMany(CoachAttendance::class);
    }

    public function getTotalHoursWorked($startDate = null, $endDate = null)
    {
        $query = $this->attendances();
        
        if ($startDate) {
            $query->where('attendance_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('attendance_date', '<=', $endDate);
        }
        
        return $query->sum('hours_worked') ?? 0;
    }

    public function getTotalSalary($startDate = null, $endDate = null)
    {
        $hours = $this->getTotalHoursWorked($startDate, $endDate);
        return $hours * $this->hourly_rate;
    }
}
