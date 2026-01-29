<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'branch_id',
        'level',
        'enrollment_date',
        'status',
        'medical_notes',
        'emergency_contact',
        'current_session_id',
        'coach_id',
        'enrollment_type',
        'period_start_date',
        'period_end_date',
        'sessions_per_month',
        'sessions_used',
        'excused_absences_allowed',
        'excused_absences_used',
        'sports_manager_notes',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'period_start_date' => 'date',
        'period_end_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function currentSession()
    {
        return $this->belongsTo(TrainingSession::class, 'current_session_id');
    }

    public function coach()
    {
        return $this->belongsTo(Coach::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function ratings()
    {
        return $this->hasMany(PlayerRating::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }

    public function familyRelationships()
    {
        return $this->hasMany(FamilyRelationship::class);
    }

    public function relatedFamilyMembers()
    {
        return $this->hasMany(FamilyRelationship::class, 'related_player_id');
    }

    public function excusedSessions()
    {
        return $this->hasMany(ExcusedSession::class);
    }

    public function getLatestRating()
    {
        return $this->ratings()->latest('rating_date')->first();
    }

    public function getAverageRating()
    {
        return $this->ratings()->avg('overall_score') ?? 0;
    }

    public function getRemainingSessions()
    {
        if ($this->enrollment_type === 'per_session') {
            return null; // Per session doesn't have a limit
        }
        return max(0, $this->sessions_per_month - $this->sessions_used);
    }

    public function getRemainingExcusedAbsences()
    {
        return max(0, ($this->excused_absences_allowed ?? 0) - ($this->excused_absences_used ?? 0));
    }

    public function getDiscountPercentage()
    {
        $relationships = $this->familyRelationships()->where('relationship_type', 'sibling')->get();
        if ($relationships->isEmpty()) {
            return 0;
        }
        return $relationships->max('discount_percentage') ?? 0;
    }

    public function canUseExcusedAbsence()
    {
        return $this->getRemainingExcusedAbsences() > 0;
    }
}
