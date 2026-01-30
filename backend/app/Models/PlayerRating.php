<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlayerRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'rated_by',
        'technique_score',
        'endurance_score',
        'speed_score',
        'attitude_score',
        'overall_score',
        'comments',
        'rating_date',
    ];

    protected $casts = [
        'rating_date' => 'date',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function ratedBy()
    {
        return $this->belongsTo(User::class, 'rated_by');
    }
}



