<?php

namespace App\Http\Controllers;

use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Services\BranchService;

class BranchController extends Controller
{
    public function __construct(
        private BranchService $branchService
    ) {}

    public function index()
    {
        $branches = $this->branchService->getAllBranches();
        return response()->json($branches);
    }

    public function store(StoreBranchRequest $request)
    {
        $branch = $this->branchService->createBranch($request->validated());
        return response()->json($branch, 201);
    }

    public function show($id)
    {
        $branch = $this->branchService->getBranchById($id);

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        return response()->json($branch);
    }

    public function update(UpdateBranchRequest $request, $id)
    {
        $branch = $this->branchService->updateBranch($id, $request->validated());

        if (!$branch) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        return response()->json($branch);
    }

    public function destroy($id)
    {
        $deleted = $this->branchService->deleteBranch($id);

        if (!$deleted) {
            return response()->json(['message' => 'Branch not found'], 404);
        }

        return response()->json(['message' => 'Branch deleted successfully']);
    }
}

