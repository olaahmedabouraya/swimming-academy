<?php

namespace App\Repositories;

use App\Models\Attendance;
use Illuminate\Database\Eloquent\Collection;

class AttendanceRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Attendance::with(['player.user', 'schedule', 'session']);

        if (isset($filters['player_id'])) {
            $query->where('player_id', $filters['player_id']);
        }

        if (isset($filters['session_id'])) {
            $query->where('session_id', $filters['session_id']);
        }

        if (isset($filters['attendance_date'])) {
            $query->where('attendance_date', $filters['attendance_date']);
        }

        if (isset($filters['date_from'])) {
            $query->where('attendance_date', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('attendance_date', '<=', $filters['date_to']);
        }

        return $query->orderBy('attendance_date', 'desc')->get();
    }

    public function findById(int $id): ?Attendance
    {
        return Attendance::with(['player.user', 'schedule', 'session'])->find($id);
    }

    public function create(array $data): Attendance
    {
        $attendance = Attendance::create($data);
        
        // Update player's sessions_used if it's a monthly enrollment and status is present
        // Don't count excused absences
        if (isset($data['player_id']) && $data['status'] === 'present') {
            $player = \App\Models\Player::find($data['player_id']);
            if ($player && $player->enrollment_type === 'monthly') {
                $player->sessions_used = ($player->sessions_used ?? 0) + 1;
                $player->save();
            }
        }
        
        return $attendance;
    }

    public function update(Attendance $attendance, array $data): bool
    {
        $oldStatus = $attendance->status;
        $updated = $attendance->update($data);
        
        // Update player's sessions_used if status changed
        // Don't count excused absences
        if ($updated && isset($data['status']) && $data['status'] !== $oldStatus) {
            $player = $attendance->player;
            if ($player && $player->enrollment_type === 'monthly') {
                if ($oldStatus === 'present' && $data['status'] !== 'present') {
                    $player->sessions_used = max(0, ($player->sessions_used ?? 0) - 1);
                } elseif ($oldStatus !== 'present' && $data['status'] === 'present') {
                    $player->sessions_used = ($player->sessions_used ?? 0) + 1;
                }
                $player->save();
            }
        }
        
        return $updated;
    }

    public function delete(Attendance $attendance): bool
    {
        $player = $attendance->player;
        $deleted = $attendance->delete();
        
        // Update player's sessions_used if attendance was deleted
        // Don't count excused absences
        if ($deleted && $player && $player->enrollment_type === 'monthly' && $attendance->status === 'present') {
            $player->sessions_used = max(0, ($player->sessions_used ?? 0) - 1);
            $player->save();
        }
        
        return $deleted;
    }

    public function getPlayersForSession($sessionId, $date): Collection
    {
        $session = \App\Models\TrainingSession::find($sessionId);
        if (!$session) {
            return collect();
        }

        return $session->players()->with(['user', 'coach.user'])->get();
    }
}
