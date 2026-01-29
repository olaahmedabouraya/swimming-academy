<?php

namespace App\Services;

use App\Repositories\PlayerRepository;
use Illuminate\Database\Eloquent\Collection;

class PlayerService
{
    public function __construct(
        private PlayerRepository $playerRepository
    ) {}

    public function getAllPlayers(array $filters = []): Collection
    {
        return $this->playerRepository->getAll($filters);
    }

    public function getPlayerById(int $id): ?array
    {
        $player = $this->playerRepository->findById($id);
        
        if (!$player) {
            return null;
        }

        return $this->formatPlayerData($player);
    }

    public function getPlayerByUserId(int $userId): ?array
    {
        $player = $this->playerRepository->findByUserId($userId);
        
        if (!$player) {
            return null;
        }

        return $this->formatPlayerData($player);
    }

    public function createPlayer(array $data): array
    {
        $player = $this->playerRepository->create($data);
        $player->load(['user', 'branch']);
        
        return $this->formatPlayerData($player);
    }

    public function createPlayerWithUser(array $data): array
    {
        // Create user first
        $user = \App\Models\User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => \Illuminate\Support\Facades\Hash::make($data['password']),
            'role' => 'player',
            'phone' => $data['phone'] ?? null,
            'date_of_birth' => $data['date_of_birth'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        // Set default values for enrollment
        $enrollmentType = $data['enrollment_type'] ?? 'monthly';
        $sessionsPerMonth = $enrollmentType === 'monthly' ? ($data['sessions_per_month'] ?? 8) : null;
        
        // Use global period dates from settings
        $periodStartDate = \App\Models\Setting::get('period_start_date');
        $periodEndDate = \App\Models\Setting::get('period_end_date');
        
        // Fallback to current month if settings not configured
        if (!$periodStartDate || !$periodEndDate) {
            $enrollmentDate = \Carbon\Carbon::parse($data['enrollment_date']);
            $periodStartDate = $enrollmentDate->copy()->startOfMonth()->format('Y-m-d');
            $periodEndDate = $enrollmentDate->copy()->endOfMonth()->format('Y-m-d');
        }

        // Create player
        $playerData = [
            'user_id' => $user->id,
            'branch_id' => $data['branch_id'] ?? null,
            'level' => $data['level'],
            'enrollment_date' => $data['enrollment_date'],
            'enrollment_type' => $enrollmentType,
            'sessions_per_month' => $sessionsPerMonth,
            'sessions_used' => 0,
            'period_start_date' => $periodStartDate,
            'period_end_date' => $periodEndDate,
            'current_session_id' => $data['current_session_id'] ?? null,
            'coach_id' => $data['coach_id'] ?? null,
            'medical_notes' => $data['medical_notes'] ?? null,
            'emergency_contact' => $data['emergency_contact'] ?? null,
        ];

        $player = $this->playerRepository->create($playerData);
        $player->load(['user', 'branch', 'currentSession', 'coach']);
        
        return $this->formatPlayerData($player);
    }

    public function updatePlayer(int $id, array $data): ?array
    {
        $player = $this->playerRepository->findById($id);
        
        if (!$player) {
            return null;
        }

        $this->playerRepository->update($player, $data);
        $player->refresh();
        $player->load(['user', 'branch']);
        
        return $this->formatPlayerData($player);
    }

    public function deletePlayer(int $id): bool
    {
        $player = $this->playerRepository->findById($id);
        
        if (!$player) {
            return false;
        }

        return $this->playerRepository->delete($player);
    }

    private function formatPlayerData($player): array
    {
        $playerArray = $player->toArray();
        $playerArray['average_rating'] = $player->getAverageRating();
        $playerArray['latest_rating'] = $player->getLatestRating();
        
        if ($player->attendances) {
            $playerArray['total_attendances'] = $player->attendances()
                ->where('status', 'present')
                ->count();
            
            $totalSchedules = $player->schedules()->count();
            $presentAttendances = $player->attendances()
                ->where('status', 'present')
                ->count();
            
            $playerArray['attendance_rate'] = $totalSchedules > 0 
                ? ($presentAttendances / $totalSchedules * 100) 
                : 0;
        }
        
        return $playerArray;
    }
}


