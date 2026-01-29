<?php

namespace App\Http\Controllers;

use App\Http\Requests\MonthlyRecord\StoreMonthlyRecordRequest;
use App\Http\Requests\MonthlyRecord\UpdateMonthlyRecordRequest;
use App\Services\MonthlyRecordService;
use Illuminate\Http\Request;

class MonthlyRecordController extends Controller
{
    public function __construct(
        private MonthlyRecordService $recordService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['branch_id', 'year', 'month']);
        $records = $this->recordService->getAllRecords($filters);

        return response()->json($records);
    }

    public function show($id)
    {
        $record = $this->recordService->getRecordById((int) $id);

        if (!$record) {
            return response()->json(['message' => 'Monthly record not found'], 404);
        }

        return response()->json($record);
    }

    public function store(StoreMonthlyRecordRequest $request)
    {
        $record = $this->recordService->createRecord($request->validated(), $request->user()->id);

        return response()->json($record, 201);
    }

    public function update(UpdateMonthlyRecordRequest $request, $id)
    {
        $record = $this->recordService->updateRecord((int) $id, $request->validated());

        if (!$record) {
            return response()->json(['message' => 'Monthly record not found'], 404);
        }

        return response()->json($record);
    }

    public function destroy($id)
    {
        $deleted = $this->recordService->deleteRecord((int) $id);

        if (!$deleted) {
            return response()->json(['message' => 'Monthly record not found'], 404);
        }

        return response()->json(['message' => 'Monthly record deleted successfully']);
    }

    public function statistics(Request $request)
    {
        $filters = $request->only(['branch_id', 'year']);
        $stats = $this->recordService->getStatistics($filters);

        return response()->json($stats);
    }
}

