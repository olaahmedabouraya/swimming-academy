<?php

namespace App\Repositories;

use App\Models\ExcusedSession;
use Illuminate\Database\Eloquent\Collection;

class ExcusedSessionRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = ExcusedSession::with(['player.user', 'originalSession', 'makeupSession', 'approvedBy']);

        if (isset($filters['player_id'])) {
            $query->where('player_id', $filters['player_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['start_date'])) {
            $query->where('original_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('original_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('original_date', 'desc')->get();
    }

    public function findById(int $id): ?ExcusedSession
    {
        return ExcusedSession::with(['player.user', 'originalSession', 'makeupSession', 'approvedBy'])->find($id);
    }

    public function create(array $data): ExcusedSession
    {
        return ExcusedSession::create($data);
    }

    public function update(ExcusedSession $excusedSession, array $data): bool
    {
        return $excusedSession->update($data);
    }

    public function delete(ExcusedSession $excusedSession): bool
    {
        return $excusedSession->delete();
    }

    public function getPendingExcusedSessions(int $playerId): Collection
    {
        return ExcusedSession::where('player_id', $playerId)
            ->where('status', 'pending')
            ->with(['originalSession', 'makeupSession'])
            ->get();
    }
}



