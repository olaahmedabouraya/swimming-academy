<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExcusedSession\StoreExcusedSessionRequest;
use App\Services\ExcusedSessionService;
use Illuminate\Http\Request;

class ExcusedSessionController extends Controller
{
    public function __construct(
        private ExcusedSessionService $excusedSessionService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['player_id', 'status', 'start_date', 'end_date']);
        $excusedSessions = $this->excusedSessionService->getAllExcusedSessions($filters);

        return response()->json($excusedSessions);
    }

    public function show($id)
    {
        $excusedSession = $this->excusedSessionService->getExcusedSessionById($id);

        if (!$excusedSession) {
            return response()->json(['message' => 'Excused session not found'], 404);
        }

        return response()->json($excusedSession);
    }

    public function store(StoreExcusedSessionRequest $request)
    {
        try {
            $excusedSession = $this->excusedSessionService->createExcusedSession(
                $request->validated(),
                $request->user()->id
            );

            return response()->json($excusedSession, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function markMakeupTaken(Request $request, $id)
    {
        $request->validate([
            'makeup_attendance_id' => 'required|exists:attendances,id',
            'makeup_session_id' => 'required|exists:training_sessions,id',
            'makeup_date' => 'required|date',
        ]);

        try {
            $excusedSession = $this->excusedSessionService->markMakeupTaken(
                $id,
                $request->makeup_attendance_id,
                $request->makeup_session_id,
                $request->makeup_date
            );

            if (!$excusedSession) {
                return response()->json(['message' => 'Excused session not found'], 404);
            }

            return response()->json($excusedSession);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function markAsDiscounted(Request $request, $id)
    {
        $request->validate([
            'fee_id' => 'required|exists:fees,id',
        ]);

        try {
            $excusedSession = $this->excusedSessionService->markAsDiscounted($id, $request->fee_id);

            if (!$excusedSession) {
                return response()->json(['message' => 'Excused session not found'], 404);
            }

            return response()->json($excusedSession);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function destroy($id)
    {
        $deleted = $this->excusedSessionService->deleteExcusedSession($id);

        if (!$deleted) {
            return response()->json(['message' => 'Excused session not found'], 404);
        }

        return response()->json(['message' => 'Excused session deleted successfully']);
    }

    public function getPendingForPlayer(Request $request, $playerId)
    {
        $excusedSessions = $this->excusedSessionService->getPendingExcusedSessions($playerId);

        return response()->json($excusedSessions);
    }
}
