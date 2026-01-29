<?php

namespace App\Repositories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Collection;

class BranchRepository
{
    public function getAll(): Collection
    {
        return Branch::withCount(['players', 'schedules'])->get();
    }

    public function findById(int $id): ?Branch
    {
        return Branch::with(['players.user', 'schedules', 'monthlyRecords'])->find($id);
    }

    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $branch->update($data);
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }
}


