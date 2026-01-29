<?php

namespace App\Repositories;

use App\Models\TrainingSession;
use Illuminate\Database\Eloquent\Collection;

class TrainingSessionRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = TrainingSession::with(['branch']);

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['is_active'])) {
            $query->where('is_active', $filters['is_active']);
        }

        if (isset($filters['day_of_week'])) {
            $query->where('day_of_week', $filters['day_of_week']);
        }

        if (isset($filters['group'])) {
            $query->where('group', $filters['group']);
        }

        if (isset($filters['date'])) {
            $date = \Carbon\Carbon::parse($filters['date'])->startOfDay();
            $dayOfWeek = $date->format('l');
            $query->where('day_of_week', $dayOfWeek)
                  ->where('is_active', true)
                  ->where(function($q) use ($date) {
                      $q->whereNull('start_date')
                        ->orWhereDate('start_date', '<=', $date);
                  })
                  ->where(function($q) use ($date) {
                      $q->whereNull('end_date')
                        ->orWhereDate('end_date', '>=', $date);
                  });
        }

        return $query->orderBy('day_of_week')->orderBy('start_time')->get();
    }

    public function findById(int $id): ?TrainingSession
    {
        return TrainingSession::with(['branch', 'players', 'coachAttendances'])->find($id);
    }

    public function create(array $data): TrainingSession
    {
        return TrainingSession::create($data);
    }

    public function update(TrainingSession $session, array $data): bool
    {
        return $session->update($data);
    }

    public function delete(TrainingSession $session): bool
    {
        return $session->delete();
    }

    public function getSessionsForDate($date, $branchId = null): Collection
    {
        $dayOfWeek = \Carbon\Carbon::parse($date)->format('l');
        $query = TrainingSession::where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->where(function($q) use ($date) {
                $q->whereNull('start_date')
                  ->orWhere('start_date', '<=', $date)
                  ->where(function($q2) use ($date) {
                      $q2->whereNull('end_date')
                         ->orWhere('end_date', '>=', $date);
                  });
            });

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        return $query->with(['branch', 'players'])->get();
    }
}


