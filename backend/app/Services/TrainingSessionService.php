<?php

namespace App\Services;

use App\Repositories\TrainingSessionRepository;
use Illuminate\Database\Eloquent\Collection;

class TrainingSessionService
{
    public function __construct(
        private TrainingSessionRepository $sessionRepository
    ) {}

    public function getAllSessions(array $filters = []): Collection
    {
        return $this->sessionRepository->getAll($filters);
    }

    public function getSessionById(int $id): ?array
    {
        $session = $this->sessionRepository->findById($id);
        return $session ? $session->toArray() : null;
    }

    public function getSessionsForDate($date, $branchId = null): Collection
    {
        return $this->sessionRepository->getSessionsForDate($date, $branchId);
    }

    public function createSession(array $data): array
    {
        $session = $this->sessionRepository->create($data);
        $session->load('branch');
        return $session->toArray();
    }

    public function updateSession(int $id, array $data): ?array
    {
        $session = $this->sessionRepository->findById($id);
        if (!$session) {
            return null;
        }

        $this->sessionRepository->update($session, $data);
        $session->refresh();
        $session->load('branch');
        return $session->toArray();
    }

    public function deleteSession(int $id): bool
    {
        $session = $this->sessionRepository->findById($id);
        if (!$session) {
            return false;
        }

        return $this->sessionRepository->delete($session);
    }
}


