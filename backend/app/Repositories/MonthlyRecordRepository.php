<?php

namespace App\Repositories;

use App\Models\MonthlyRecord;
use Illuminate\Database\Eloquent\Collection;

class MonthlyRecordRepository
{
    public function getAll(array $filters = []): Collection
    {
        $query = MonthlyRecord::with(['branch', 'creator']);

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        if (isset($filters['month'])) {
            $query->where('month', $filters['month']);
        }

        return $query->orderBy('year', 'desc')->orderBy('month', 'desc')->get();
    }

    public function findById(int $id): ?MonthlyRecord
    {
        return MonthlyRecord::with(['branch', 'creator'])->find($id);
    }

    public function create(array $data): MonthlyRecord
    {
        return MonthlyRecord::create($data);
    }

    public function update(MonthlyRecord $record, array $data): bool
    {
        return $record->update($data);
    }

    public function delete(MonthlyRecord $record): bool
    {
        return $record->delete();
    }

    public function getStatistics(array $filters = []): array
    {
        $query = MonthlyRecord::query();

        if (isset($filters['branch_id'])) {
            $query->where('branch_id', $filters['branch_id']);
        }

        if (isset($filters['year'])) {
            $query->where('year', $filters['year']);
        }

        return [
            'total_revenue' => $query->sum('revenue'),
            'total_enrollments' => $query->sum('new_enrollments'),
            'average_selling_rate' => $query->avg('selling_rate'),
            'total_sessions' => $query->sum('total_sessions_conducted'),
            'total_attendance' => $query->sum('total_attendance'),
        ];
    }
}



