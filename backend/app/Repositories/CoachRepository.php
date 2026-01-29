<?php

namespace App\Repositories;

use App\Models\Coach;
use Illuminate\Database\Eloquent\Collection;

class CoachRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Coach::with(['user', 'branch']);

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        return $query->get();
    }

    public function findById(int $id): ?Coach
    {
        return Coach::with(['user', 'branch', 'players', 'attendances'])->find($id);
    }

    public function create(array $data): Coach
    {
        return Coach::create($data);
    }

    public function update(Coach $coach, array $data): bool
    {
        return $coach->update($data);
    }

    public function delete(Coach $coach): bool
    {
        return $coach->delete();
    }

    public function getCoachAttendanceStats(int $coachId, $startDate = null, $endDate = null): array
    {
        $coach = $this->findById($coachId);
        if (!$coach) {
            return [];
        }

        $query = $coach->attendances();
        
        if ($startDate) {
            $query->where('attendance_date', '>=', $startDate);
        }
        
        if ($endDate) {
            $query->where('attendance_date', '<=', $endDate);
        }

        $attendances = $query->get();
        
        return [
            'total_attendances' => $attendances->count(),
            'total_hours' => $attendances->sum('hours_worked'),
            'total_salary' => $coach->getTotalSalary($startDate, $endDate),
            'late_count' => $attendances->where('is_late', true)->count(),
        ];
    }
}


