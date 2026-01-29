<?php

namespace App\Services;

use App\Repositories\CoachRepository;
use Illuminate\Database\Eloquent\Collection;

class CoachService
{
    public function __construct(
        private CoachRepository $coachRepository
    ) {}

    public function getAllCoaches(array $filters = []): Collection
    {
        return $this->coachRepository->getAll($filters);
    }

    public function getCoachById(int $id): ?array
    {
        $coach = $this->coachRepository->findById($id);
        if (!$coach) {
            return null;
        }

        $coachArray = $coach->toArray();
        $coachArray['total_players'] = $coach->players()->count();
        return $coachArray;
    }

    public function createCoach(array $data): array
    {
        $coach = $this->coachRepository->create($data);
        $coach->load(['user', 'branch']);
        return $coach->toArray();
    }

    public function updateCoach(int $id, array $data): ?array
    {
        $coach = $this->coachRepository->findById($id);
        if (!$coach) {
            return null;
        }

        $this->coachRepository->update($coach, $data);
        $coach->refresh();
        $coach->load(['user', 'branch']);
        return $coach->toArray();
    }

    public function deleteCoach(int $id): bool
    {
        $coach = $this->coachRepository->findById($id);
        if (!$coach) {
            return false;
        }

        return $this->coachRepository->delete($coach);
    }

    public function getCoachStats(int $coachId, $startDate = null, $endDate = null): array
    {
        return $this->coachRepository->getCoachAttendanceStats($coachId, $startDate, $endDate);
    }

    public function assignPlayerToCoach(int $coachId, int $playerId): bool
    {
        $coach = $this->coachRepository->findById($coachId);
        if (!$coach) {
            return false;
        }

        $player = \App\Models\Player::find($playerId);
        if (!$player) {
            return false;
        }

        $player->coach_id = $coachId;
        return $player->save();
    }

    public function removePlayerFromCoach(int $playerId): bool
    {
        $player = \App\Models\Player::find($playerId);
        if (!$player) {
            return false;
        }

        $player->coach_id = null;
        return $player->save();
    }
}


