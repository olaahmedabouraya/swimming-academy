<?php

namespace App\Services;

use App\Repositories\AttendanceRepository;
use App\Repositories\TrainingSessionRepository;
use Illuminate\Database\Eloquent\Collection;

class AttendanceService
{
    public function __construct(
        private AttendanceRepository $attendanceRepository,
        private TrainingSessionRepository $sessionRepository
    ) {}

    public function getAllAttendances(array $filters = []): Collection
    {
        return $this->attendanceRepository->getAll($filters);
    }

    public function getAttendanceById(int $id): ?array
    {
        $attendance = $this->attendanceRepository->findById($id);
        return $attendance ? $attendance->toArray() : null;
    }

    public function getPlayersForSession($sessionId, $date): Collection
    {
        return $this->attendanceRepository->getPlayersForSession($sessionId, $date);
    }

    public function getSessionsForDate($date, $branchId = null): Collection
    {
        return $this->sessionRepository->getSessionsForDate($date, $branchId);
    }

    public function createAttendance(array $data): array
    {
        $attendance = $this->attendanceRepository->create($data);
        $attendance->load(['player.user', 'schedule', 'session']);
        
        return $attendance->toArray();
    }

    public function updateAttendance(int $id, array $data): ?array
    {
        $attendance = $this->attendanceRepository->findById($id);
        
        if (!$attendance) {
            return null;
        }

        $this->attendanceRepository->update($attendance, $data);
        $attendance->refresh();
        $attendance->load(['player.user', 'schedule', 'session']);
        
        return $attendance->toArray();
    }

    public function deleteAttendance(int $id): bool
    {
        $attendance = $this->attendanceRepository->findById($id);
        
        if (!$attendance) {
            return false;
        }

        return $this->attendanceRepository->delete($attendance);
    }

    public function markMultipleAttendances(array $attendances): array
    {
        $results = [];
        foreach ($attendances as $attendanceData) {
            try {
                $attendance = $this->attendanceRepository->create($attendanceData);
                $attendance->load(['player.user', 'session']);
                $results[] = $attendance->toArray();
            } catch (\Exception $e) {
                $results[] = ['error' => $e->getMessage()];
            }
        }
        return $results;
    }
}
