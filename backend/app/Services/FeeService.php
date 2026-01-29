<?php

namespace App\Services;

use App\Repositories\FeeRepository;
use App\Repositories\ExcusedSessionRepository;
use Illuminate\Database\Eloquent\Collection;

class FeeService
{
    public function __construct(
        private FeeRepository $feeRepository,
        private ExcusedSessionRepository $excusedSessionRepository
    ) {}

    public function getAllFees(array $filters = []): Collection
    {
        return $this->feeRepository->getAll($filters);
    }

    public function getFeeById(int $id): ?array
    {
        $fee = $this->feeRepository->findById($id);
        return $fee ? $fee->toArray() : null;
    }

    public function createFee(array $data): array
    {
        $fee = $this->feeRepository->create($data);
        $fee->load(['player.user', 'recordedBy']);
        return $fee->toArray();
    }

    public function createRenewalFeeWithDiscounts(int $playerId, float $baseAmount, $paymentDate, int $recordedBy, $notes = null): array
    {
        $player = \App\Models\Player::find($playerId);
        if (!$player) {
            throw new \Exception('Player not found');
        }

        // Get pending excused sessions that should be discounted
        $pendingExcusedSessions = $this->excusedSessionRepository->getPendingExcusedSessions($playerId);
        
        // Calculate discount amount (one session worth per excused session)
        // Assuming base amount is for full month, discount is base_amount / sessions_per_month per excused session
        $sessionsPerMonth = $player->sessions_per_month ?? 8;
        $discountPerSession = $baseAmount / $sessionsPerMonth;
        $totalDiscount = $pendingExcusedSessions->count() * $discountPerSession;
        
        // Apply family discount if applicable
        $familyDiscount = $player->getDiscountPercentage();
        $familyDiscountAmount = ($baseAmount - $totalDiscount) * ($familyDiscount / 100);
        
        $finalAmount = $baseAmount - $totalDiscount - $familyDiscountAmount;
        
        $feeData = [
            'player_id' => $playerId,
            'fee_type' => 'renewal',
            'amount' => max(0, $finalAmount), // Ensure non-negative
            'payment_date' => $paymentDate,
            'payment_method' => 'cash',
            'notes' => $notes . ($totalDiscount > 0 ? " (Discounted: {$totalDiscount} for excused sessions)" : ''),
            'recorded_by' => $recordedBy,
        ];

        $fee = $this->feeRepository->create($feeData);
        
        // Mark all pending excused sessions as discounted
        foreach ($pendingExcusedSessions as $excusedSession) {
            $excusedSession->markAsDiscounted($fee->id);
        }
        
        $fee->load(['player.user', 'recordedBy']);
        return $fee->toArray();
    }

    public function updateFee(int $id, array $data): ?array
    {
        $fee = $this->feeRepository->findById($id);
        if (!$fee) {
            return null;
        }

        $this->feeRepository->update($fee, $data);
        $fee->refresh();
        $fee->load(['player.user', 'recordedBy']);
        return $fee->toArray();
    }

    public function deleteFee(int $id): bool
    {
        $fee = $this->feeRepository->findById($id);
        if (!$fee) {
            return false;
        }

        return $this->feeRepository->delete($fee);
    }

    public function getTotalRevenue($startDate = null, $endDate = null): float
    {
        return $this->feeRepository->getTotalRevenue($startDate, $endDate);
    }

    public function recordWithdrawal(int $playerId, float $amount, $paymentDate, int $recordedBy, $notes = null): array
    {
        $data = [
            'player_id' => $playerId,
            'fee_type' => 'withdrawal',
            'amount' => $amount,
            'payment_date' => $paymentDate,
            'payment_method' => 'cash',
            'notes' => $notes,
            'recorded_by' => $recordedBy,
        ];

        return $this->createFee($data);
    }
}
