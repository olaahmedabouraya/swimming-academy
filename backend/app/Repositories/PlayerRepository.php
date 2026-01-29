<?php

namespace App\Repositories;

use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class PlayerRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Player::with(['user', 'branch', 'schedules', 'ratings']);

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['level'])) {
            $query->where('level', $filters['level']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->get();
    }

    public function findById(int $id): ?Player
    {
        return Player::with(['user', 'branch', 'schedules', 'ratings', 'attendances'])
            ->find($id);
    }

    public function findByUserId(int $userId): ?Player
    {
        return Player::with(['user', 'branch', 'schedules.branch', 'ratings.ratedBy', 'attendances.schedule'])
            ->where('user_id', $userId)
            ->first();
    }

    public function create(array $data): Player
    {
        return Player::create($data);
    }

    public function update(Player $player, array $data): bool
    {
        return $player->update($data);
    }

    public function delete(Player $player): bool
    {
        return $player->delete();
    }
}


