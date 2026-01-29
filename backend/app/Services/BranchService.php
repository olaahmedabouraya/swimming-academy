<?php

namespace App\Services;

use App\Repositories\BranchRepository;
use Illuminate\Database\Eloquent\Collection;

class BranchService
{
    public function __construct(
        private BranchRepository $branchRepository
    ) {}

    public function getAllBranches(): Collection
    {
        return $this->branchRepository->getAll();
    }

    public function getBranchById(int $id): ?array
    {
        $branch = $this->branchRepository->findById($id);
        return $branch ? $branch->toArray() : null;
    }

    public function createBranch(array $data): array
    {
        $branch = $this->branchRepository->create($data);
        return $branch->toArray();
    }

    public function updateBranch(int $id, array $data): ?array
    {
        $branch = $this->branchRepository->findById($id);
        
        if (!$branch) {
            return null;
        }

        $this->branchRepository->update($branch, $data);
        $branch->refresh();
        
        return $branch->toArray();
    }

    public function deleteBranch(int $id): bool
    {
        $branch = $this->branchRepository->findById($id);
        
        if (!$branch) {
            return false;
        }

        return $this->branchRepository->delete($branch);
    }
}


