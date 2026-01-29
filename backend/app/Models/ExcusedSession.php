<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcusedSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'original_attendance_id',
        'original_session_id',
        'original_date',
        'excuse_reason',
        'status',
        'makeup_attendance_id',
        'makeup_session_id',
        'makeup_date',
        'discounted_from_fee',
        'discounted_fee_id',
        'approved_by',
    ];

    protected $casts = [
        'original_date' => 'date',
        'makeup_date' => 'date',
        'discounted_from_fee' => 'boolean',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function originalAttendance()
    {
        return $this->belongsTo(Attendance::class, 'original_attendance_id');
    }

    public function originalSession()
    {
        return $this->belongsTo(TrainingSession::class, 'original_session_id');
    }

    public function makeupAttendance()
    {
        return $this->belongsTo(Attendance::class, 'makeup_attendance_id');
    }

    public function makeupSession()
    {
        return $this->belongsTo(TrainingSession::class, 'makeup_session_id');
    }

    public function discountedFee()
    {
        return $this->belongsTo(Fee::class, 'discounted_fee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function markAsMakeupTaken($makeupAttendanceId, $makeupSessionId, $makeupDate)
    {
        $this->update([
            'status' => 'makeup_taken',
            'makeup_attendance_id' => $makeupAttendanceId,
            'makeup_session_id' => $makeupSessionId,
            'makeup_date' => $makeupDate,
        ]);
    }

    public function markAsDiscounted($feeId)
    {
        $this->update([
            'status' => 'discounted',
            'discounted_from_fee' => true,
            'discounted_fee_id' => $feeId,
        ]);
    }
}
