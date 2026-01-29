<?php

namespace App\Http\Controllers;

use App\Http\Requests\TrainingSession\StoreTrainingSessionRequest;
use App\Http\Requests\TrainingSession\UpdateTrainingSessionRequest;
use App\Services\TrainingSessionService;
use Illuminate\Http\Request;

class TrainingSessionController extends Controller
{
    public function __construct(
        private TrainingSessionService $sessionService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['branch_id', 'group', 'is_active', 'day_of_week', 'date']);
        $sessions = $this->sessionService->getAllSessions($filters);

        return response()->json($sessions);
    }

    public function show($id)
    {
        $session = $this->sessionService->getSessionById($id);

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        return response()->json($session);
    }

    public function store(StoreTrainingSessionRequest $request)
    {
        $session = $this->sessionService->createSession($request->validated());

        return response()->json($session, 201);
    }

    public function update(UpdateTrainingSessionRequest $request, $id)
    {
        $session = $this->sessionService->updateSession($id, $request->validated());

        if (!$session) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        return response()->json($session);
    }

    public function destroy($id)
    {
        $deleted = $this->sessionService->deleteSession($id);

        if (!$deleted) {
            return response()->json(['message' => 'Session not found'], 404);
        }

        return response()->json(['message' => 'Session deleted successfully']);
    }

    public function getSessionsForDate(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        $sessions = $this->sessionService->getSessionsForDate($date, $branchId);

        return response()->json($sessions);
    }
}
