<?php

namespace App\Services;

use App\Repositories\MonthlyRecordRepository;
use Illuminate\Database\Eloquent\Collection;

class MonthlyRecordService
{
    public function __construct(
        private MonthlyRecordRepository $recordRepository
    ) {}

    public function getAllRecords(array $filters = []): Collection
    {
        return $this->recordRepository->getAll($filters);
    }

    public function getRecordById(int $id): ?array
    {
        $record = $this->recordRepository->findById($id);
        if ($record) {
            $record->load(['branch', 'creator']);
            return $record->toArray();
        }
        return null;
    }

    public function createRecord(array $data, int $createdBy): array
    {
        $data['created_by'] = $createdBy;
        $record = $this->recordRepository->create($data);
        $record->load(['branch', 'creator']);
        
        return $record->toArray();
    }

    public function updateRecord(int $id, array $data): ?array
    {
        $record = $this->recordRepository->findById($id);
        
        if (!$record) {
            return null;
        }

        $this->recordRepository->update($record, $data);
        $record->refresh();
        $record->load(['branch', 'creator']);
        
        return $record->toArray();
    }

    public function deleteRecord(int $id): bool
    {
        $record = $this->recordRepository->findById($id);
        
        if (!$record) {
            return false;
        }

        return $this->recordRepository->delete($record);
    }

    public function getStatistics(array $filters = []): array
    {
        return $this->recordRepository->getStatistics($filters);
    }
}


