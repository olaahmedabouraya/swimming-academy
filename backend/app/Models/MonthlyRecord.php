<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'year',
        'month',
        'revenue',
        'new_enrollments',
        'total_active_players',
        'selling_rate',
        'total_sessions_conducted',
        'total_attendance',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'revenue' => 'decimal:2',
        'selling_rate' => 'decimal:2',
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}



