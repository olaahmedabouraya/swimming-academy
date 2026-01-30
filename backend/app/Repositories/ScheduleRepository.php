<?php

namespace App\Repositories;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Schedule::with(['player.user', 'branch']);

        if (isset($filters['player_id'])) {
            $query->where('player_id', $filters['player_id']);
        }

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        return $query->get();
    }

    public function findById(int $id): ?Schedule
    {
        return Schedule::with(['player.user', 'branch'])->find($id);
    }

    public function create(array $data): Schedule
    {
        return Schedule::create($data);
    }

    public function update(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    public function delete(Schedule $schedule): bool
    {
        return $schedule->delete();
    }
}



