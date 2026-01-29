<?php

namespace App\Repositories;

use App\Models\PlayerRating;
use Illuminate\Database\Eloquent\Collection;

class PlayerRatingRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = PlayerRating::with(['player.user', 'ratedBy']);

        if (isset($filters['player_id'])) {
            $query->where('player_id', $filters['player_id']);
        }

        return $query->orderBy('rating_date', 'desc')->get();
    }

    public function findById(int $id): ?PlayerRating
    {
        return PlayerRating::with(['player.user', 'ratedBy'])->find($id);
    }

    public function create(array $data): PlayerRating
    {
        return PlayerRating::create($data);
    }

    public function update(PlayerRating $rating, array $data): bool
    {
        return $rating->update($data);
    }

    public function delete(PlayerRating $rating): bool
    {
        return $rating->delete();
    }
}


