<?php

namespace App\Http\Controllers;

use App\Http\Requests\Fee\StoreFeeRequest;
use App\Services\FeeService;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public function __construct(
        private FeeService $feeService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['player_id', 'fee_type', 'start_date', 'end_date']);
        $fees = $this->feeService->getAllFees($filters);

        return response()->json($fees);
    }

    public function show($id)
    {
        $fee = $this->feeService->getFeeById($id);

        if (!$fee) {
            return response()->json(['message' => 'Fee not found'], 404);
        }

        return response()->json($fee);
    }

    public function store(StoreFeeRequest $request)
    {
        $data = $request->validated();
        $data['recorded_by'] = $request->user()->id;
        
        $fee = $this->feeService->createFee($data);

        return response()->json($fee, 201);
    }

    public function destroy($id)
    {
        $deleted = $this->feeService->deleteFee($id);

        if (!$deleted) {
            return response()->json(['message' => 'Fee not found'], 404);
        }

        return response()->json(['message' => 'Fee deleted successfully']);
    }

    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $revenue = $this->feeService->getTotalRevenue($startDate, $endDate);

        return response()->json(['total_revenue' => $revenue]);
    }

    public function recordWithdrawal(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $withdrawal = $this->feeService->recordWithdrawal(
            $request->player_id,
            $request->amount,
            $request->payment_date,
            $request->user()->id,
            $request->notes
        );

        return response()->json($withdrawal, 201);
    }

    public function createRenewalWithDiscounts(Request $request)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
            'base_amount' => 'required|numeric|min:0',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        try {
            $fee = $this->feeService->createRenewalFeeWithDiscounts(
                $request->player_id,
                $request->base_amount,
                $request->payment_date,
                $request->user()->id,
                $request->notes
            );

            return response()->json($fee, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}
