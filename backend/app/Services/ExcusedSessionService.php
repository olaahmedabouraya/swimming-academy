<?php

namespace App\Services;

use App\Repositories\ExcusedSessionRepository;
use App\Repositories\PlayerRepository;
use App\Models\Player;
use Illuminate\Database\Eloquent\Collection;

class ExcusedSessionService
{
    public function __construct(
        private ExcusedSessionRepository $excusedSessionRepository,
        private PlayerRepository $playerRepository
    ) {}

    public function getAllExcusedSessions(array $filters = []): Collection
    {
        return $this->excusedSessionRepository->getAll($filters);
    }

    public function getExcusedSessionById(int $id): ?array
    {
        $excusedSession = $this->excusedSessionRepository->findById($id);
        return $excusedSession ? $excusedSession->toArray() : null;
    }

    public function createExcusedSession(array $data, int $approvedBy): array
    {
        $player = $this->playerRepository->findById($data['player_id']);
        
        if (!$player) {
            throw new \Exception('Player not found');
        }

        // Check if player has remaining excused absences
        if (!$player->canUseExcusedAbsence()) {
            throw new \Exception('Player has no remaining excused absences');
        }

        $data['approved_by'] = $approvedBy;
        $data['status'] = 'pending';
        
        $excusedSession = $this->excusedSessionRepository->create($data);
        
        // Increment excused absences used
        $player->excused_absences_used = ($player->excused_absences_used ?? 0) + 1;
        $player->save();
        
        $excusedSession->load(['player.user', 'originalSession', 'approvedBy']);
        return $excusedSession->toArray();
    }

    public function markMakeupTaken(int $id, int $makeupAttendanceId, int $makeupSessionId, $makeupDate): ?array
    {
        $excusedSession = $this->excusedSessionRepository->findById($id);
        
        if (!$excusedSession) {
            return null;
        }

        if ($excusedSession->status !== 'pending') {
            throw new \Exception('Excused session is not pending');
        }

        $excusedSession->markAsMakeupTaken($makeupAttendanceId, $makeupSessionId, $makeupDate);
        $excusedSession->load(['player.user', 'originalSession', 'makeupSession', 'approvedBy']);
        
        return $excusedSession->toArray();
    }

    public function markAsDiscounted(int $id, int $feeId): ?array
    {
        $excusedSession = $this->excusedSessionRepository->findById($id);
        
        if (!$excusedSession) {
            return null;
        }

        if ($excusedSession->status !== 'pending') {
            throw new \Exception('Excused session is not pending');
        }

        $excusedSession->markAsDiscounted($feeId);
        $excusedSession->load(['player.user', 'originalSession', 'discountedFee', 'approvedBy']);
        
        return $excusedSession->toArray();
    }

    public function deleteExcusedSession(int $id): bool
    {
        $excusedSession = $this->excusedSessionRepository->findById($id);
        
        if (!$excusedSession) {
            return false;
        }

        // Decrement excused absences used if status is pending
        if ($excusedSession->status === 'pending') {
            $player = $excusedSession->player;
            $player->excused_absences_used = max(0, ($player->excused_absences_used ?? 0) - 1);
            $player->save();
        }

        return $this->excusedSessionRepository->delete($excusedSession);
    }

    public function getPendingExcusedSessions(int $playerId): Collection
    {
        return $this->excusedSessionRepository->getPendingExcusedSessions($playerId);
    }
}



