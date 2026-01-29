<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyRelationship extends Model
{
    use HasFactory;

    protected $fillable = [
        'player_id',
        'related_player_id',
        'relationship_type',
        'discount_percentage',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
    ];

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function relatedPlayer()
    {
        return $this->belongsTo(Player::class, 'related_player_id');
    }
}
