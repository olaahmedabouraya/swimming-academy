<?php

namespace App\Repositories;

use App\Models\Fee;
use Illuminate\Database\Eloquent\Collection;

class FeeRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = Fee::with(['player.user', 'recordedBy']);

        if (isset($filters['player_id'])) {
            $query->where('player_id', $filters['player_id']);
        }

        if (isset($filters['fee_type'])) {
            $query->where('fee_type', $filters['fee_type']);
        }

        if (isset($filters['start_date'])) {
            $query->where('payment_date', '>=', $filters['start_date']);
        }

        if (isset($filters['end_date'])) {
            $query->where('payment_date', '<=', $filters['end_date']);
        }

        return $query->orderBy('payment_date', 'desc')->get();
    }

    public function findById(int $id): ?Fee
    {
        return Fee::with(['player.user', 'recordedBy'])->find($id);
    }

    public function create(array $data): Fee
    {
        return Fee::create($data);
    }

    public function update(Fee $fee, array $data): bool
    {
        return $fee->update($data);
    }

    public function delete(Fee $fee): bool
    {
        return $fee->delete();
    }

    public function getTotalRevenue($startDate = null, $endDate = null): float
    {
        $query = Fee::query();

        if ($startDate) {
            $query->where('payment_date', '>=', $startDate);
        }

        if ($endDate) {
            $query->where('payment_date', '<=', $endDate);
        }

        $fees = $query->get();
        
        $revenue = 0;
        foreach ($fees as $fee) {
            if ($fee->isWithdrawal()) {
                $revenue -= $fee->amount;
            } else {
                $revenue += $fee->amount;
            }
        }

        return $revenue;
    }
}



