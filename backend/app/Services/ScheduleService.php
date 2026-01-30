<?php

namespace App\Services;

use App\Repositories\ScheduleRepository;
use Illuminate\Database\Eloquent\Collection;

class ScheduleService
{
    public function __construct(
        private ScheduleRepository $scheduleRepository
    ) {}

    public function getAllSchedules(array $filters = []): Collection
    {
        return $this->scheduleRepository->getAll($filters);
    }

    public function getScheduleById(int $id): ?array
    {
        $schedule = $this->scheduleRepository->findById($id);
        return $schedule ? $schedule->toArray() : null;
    }

    public function createSchedule(array $data): array
    {
        $schedule = $this->scheduleRepository->create($data);
        $schedule->load(['player.user', 'branch']);
        
        return $schedule->toArray();
    }

    public function updateSchedule(int $id, array $data): ?array
    {
        $schedule = $this->scheduleRepository->findById($id);
        
        if (!$schedule) {
            return null;
        }

        $this->scheduleRepository->update($schedule, $data);
        $schedule->refresh();
        $schedule->load(['player.user', 'branch']);
        
        return $schedule->toArray();
    }

    public function deleteSchedule(int $id): bool
    {
        $schedule = $this->scheduleRepository->findById($id);
        
        if (!$schedule) {
            return false;
        }

        return $this->scheduleRepository->delete($schedule);
    }
}



