<?php

namespace App\Http\Controllers;

use App\Http\Requests\Coach\StoreCoachRequest;
use App\Http\Requests\Coach\UpdateCoachRequest;
use App\Services\CoachService;
use Illuminate\Http\Request;

class CoachController extends Controller
{
    public function __construct(
        private CoachService $coachService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['branch_id', 'is_active']);
        $coaches = $this->coachService->getAllCoaches($filters);

        return response()->json($coaches);
    }

    public function show($id)
    {
        $coach = $this->coachService->getCoachById($id);

        if (!$coach) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        return response()->json($coach);
    }

    public function store(StoreCoachRequest $request)
    {
        $coach = $this->coachService->createCoach($request->validated());

        return response()->json($coach, 201);
    }

    public function update(UpdateCoachRequest $request, $id)
    {
        $coach = $this->coachService->updateCoach($id, $request->validated());

        if (!$coach) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        return response()->json($coach);
    }

    public function destroy($id)
    {
        $deleted = $this->coachService->deleteCoach($id);

        if (!$deleted) {
            return response()->json(['message' => 'Coach not found'], 404);
        }

        return response()->json(['message' => 'Coach deleted successfully']);
    }

    public function stats(Request $request, $id)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        
        $stats = $this->coachService->getCoachStats($id, $startDate, $endDate);

        return response()->json($stats);
    }

    public function assignPlayer(Request $request, $coachId)
    {
        $request->validate([
            'player_id' => 'required|exists:players,id',
        ]);

        $assigned = $this->coachService->assignPlayerToCoach($coachId, $request->player_id);

        if (!$assigned) {
            return response()->json(['message' => 'Failed to assign player'], 400);
        }

        return response()->json(['message' => 'Player assigned successfully']);
    }

    public function removePlayer(Request $request, $playerId)
    {
        $removed = $this->coachService->removePlayerFromCoach($playerId);

        if (!$removed) {
            return response()->json(['message' => 'Failed to remove player'], 400);
        }

        return response()->json(['message' => 'Player removed successfully']);
    }
}
