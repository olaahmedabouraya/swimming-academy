<?php

namespace App\Http\Controllers;

use App\Http\Requests\Player\StorePlayerRequest;
use App\Http\Requests\Player\UpdatePlayerRequest;
use App\Services\PlayerService;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function __construct(
        private PlayerService $playerService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['branch_id', 'level', 'status']);
        $players = $this->playerService->getAllPlayers($filters);

        return response()->json($players);
    }

    public function show($id)
    {
        $player = $this->playerService->getPlayerById($id);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }

    public function store(StorePlayerRequest $request)
    {
        $player = $this->playerService->createPlayer($request->validated());

        return response()->json($player, 201);
    }

    public function createWithUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string',
            'branch_id' => 'nullable|exists:branches,id',
            'level' => 'required|in:beginner,intermediate,advanced,professional',
            'enrollment_date' => 'required|date',
            'enrollment_type' => 'nullable|in:monthly,per_session',
            'sessions_per_month' => 'nullable|integer|min:1|max:12',
            'period_start_date' => 'nullable|date',
            'period_end_date' => 'nullable|date|after:period_start_date',
            'current_session_id' => 'nullable|exists:training_sessions,id',
            'coach_id' => 'nullable|exists:coaches,id',
            'medical_notes' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:500',
        ]);

        $player = $this->playerService->createPlayerWithUser($request->all());

        return response()->json($player, 201);
    }

    public function update(UpdatePlayerRequest $request, $id)
    {
        $player = $this->playerService->updatePlayer($id, $request->validated());

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }

    public function destroy($id)
    {
        $deleted = $this->playerService->deletePlayer($id);

        if (!$deleted) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json(['message' => 'Player deleted successfully']);
    }

    public function myProfile(Request $request)
    {
        $player = $this->playerService->getPlayerByUserId($request->user()->id);

        if (!$player) {
            return response()->json(['message' => 'Player profile not found'], 404);
        }

        return response()->json($player);
    }

    public function moveSession(Request $request, $id)
    {
        $request->validate([
            'session_id' => 'required|exists:training_sessions,id',
        ]);

        $player = $this->playerService->updatePlayer($id, [
            'current_session_id' => $request->session_id
        ]);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }

    public function updateSportsManagerNotes(Request $request, $id)
    {
        $request->validate([
            'sports_manager_notes' => 'required|string|max:5000',
        ]);

        $player = $this->playerService->updatePlayer($id, [
            'sports_manager_notes' => $request->sports_manager_notes
        ]);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }

    public function setExcusedAbsences(Request $request, $id)
    {
        $request->validate([
            'excused_absences_allowed' => 'required|integer|min:0',
        ]);

        $player = $this->playerService->updatePlayer($id, [
            'excused_absences_allowed' => $request->excused_absences_allowed
        ]);

        if (!$player) {
            return response()->json(['message' => 'Player not found'], 404);
        }

        return response()->json($player);
    }
}
