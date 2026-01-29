<?php

namespace App\Http\Controllers;

use App\Http\Requests\Attendance\StoreAttendanceRequest;
use App\Http\Requests\Attendance\UpdateAttendanceRequest;
use App\Services\AttendanceService;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function __construct(
        private AttendanceService $attendanceService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['player_id', 'session_id', 'attendance_date', 'date_from', 'date_to']);
        $attendances = $this->attendanceService->getAllAttendances($filters);

        return response()->json($attendances);
    }

    public function store(StoreAttendanceRequest $request)
    {
        $attendance = $this->attendanceService->createAttendance($request->validated());

        return response()->json($attendance, 201);
    }

    public function update(UpdateAttendanceRequest $request, $id)
    {
        $attendance = $this->attendanceService->updateAttendance($id, $request->validated());

        if (!$attendance) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        return response()->json($attendance);
    }

    public function destroy($id)
    {
        $deleted = $this->attendanceService->deleteAttendance($id);

        if (!$deleted) {
            return response()->json(['message' => 'Attendance not found'], 404);
        }

        return response()->json(['message' => 'Attendance deleted successfully']);
    }

    public function getSessionsForDate(Request $request)
    {
        $date = $request->input('date', now()->toDateString());
        $branchId = $request->input('branch_id');
        
        $sessions = $this->attendanceService->getSessionsForDate($date, $branchId);

        return response()->json($sessions);
    }

    public function getPlayersForSession(Request $request, $sessionId)
    {
        $date = $request->input('date', now()->toDateString());
        
        $players = $this->attendanceService->getPlayersForSession($sessionId, $date);

        return response()->json($players);
    }

    public function markMultiple(Request $request)
    {
        $request->validate([
            'attendances' => 'required|array',
            'attendances.*.player_id' => 'required|exists:players,id',
            'attendances.*.session_id' => 'required|exists:training_sessions,id',
            'attendances.*.attendance_date' => 'required|date',
            'attendances.*.status' => 'required|in:present,absent,late,excused',
        ]);

        $results = $this->attendanceService->markMultipleAttendances($request->attendances);

        return response()->json($results, 201);
    }
}
