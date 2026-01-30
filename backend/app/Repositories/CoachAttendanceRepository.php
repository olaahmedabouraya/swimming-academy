<?php

namespace App\Repositories;

use App\Models\CoachAttendance;
use Illuminate\Database\Eloquent\Collection;

class CoachAttendanceRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = CoachAttendance::with(['coach.user', 'session', 'recordedBy']);

        if (isset($filters['coach_id'])) {
            $query->where('coach_id', $filters['coach_id']);
        }

        if (isset($filters['session_id'])) {
            $query->where('session_id', $filters['session_id']);
        }

        if (isset($filters['attendance_date'])) {
            $query->where('attendance_date', $filters['attendance_date']);
        }

        if (isset($filters['start_date'])) {
            $query->where('attendance_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('attendance_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('attendance_date', 'desc')->get();
    }

    public function findById(int $id): ?CoachAttendance
    {
        return CoachAttendance::with(['coach.user', 'session', 'recordedBy'])->find($id);
    }

    public function create(array $data): CoachAttendance
    {
        $attendance = CoachAttendance::create($data);
        $attendance->calculateHoursWorked();
        $attendance->save();
        return $attendance;
    }

    public function update(CoachAttendance $attendance, array $data): bool
    {
        $updated = $attendance->update($data);
        if ($updated) {
            $attendance->calculateHoursWorked();
            $attendance->save();
        }
        return $updated;
    }

    public function delete(CoachAttendance $attendance): bool
    {
        return $attendance->delete();
    }
}



