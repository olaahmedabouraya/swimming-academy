<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'fee_type',
        'amount',
        'payment_date',
        'payment_method',
        'reference_number',
        'notes',
        'recorded_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function isWithdrawal()
    {
        return $this->fee_type === 'withdrawal';
    }
}
