<?php

namespace App\Services;

use App\Repositories\CoachAttendanceRepository;
use Illuminate\Database\Eloquent\Collection;

class CoachAttendanceService
{
    public function __construct(
        private CoachAttendanceRepository $coachAttendanceRepository
    ) {}

    public function getAllAttendances(array $filters = []): Collection
    {
        return $this->coachAttendanceRepository->getAll($filters);
    }

    public function getAttendanceById(int $id): ?array
    {
        $attendance = $this->coachAttendanceRepository->findById($id);
        return $attendance ? $attendance->toArray() : null;
    }

    public function createAttendance(array $data): array
    {
        $attendance = $this->coachAttendanceRepository->create($data);
        $attendance->load(['coach.user', 'session', 'recordedBy']);
        return $attendance->toArray();
    }

    public function updateAttendance(int $id, array $data): ?array
    {
        $attendance = $this->coachAttendanceRepository->findById($id);
        if (!$attendance) {
            return null;
        }

        $this->coachAttendanceRepository->update($attendance, $data);
        $attendance->refresh();
        $attendance->load(['coach.user', 'session', 'recordedBy']);
        return $attendance->toArray();
    }

    public function deleteAttendance(int $id): bool
    {
        $attendance = $this->coachAttendanceRepository->findById($id);
        if (!$attendance) {
            return false;
        }

        return $this->coachAttendanceRepository->delete($attendance);
    }
}



