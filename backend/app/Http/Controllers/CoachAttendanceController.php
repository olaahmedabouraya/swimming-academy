<?php

namespace App\Http\Controllers;

use App\Http\Requests\CoachAttendance\StoreCoachAttendanceRequest;
use App\Services\CoachAttendanceService;
use Illuminate\Http\Request;

class CoachAttendanceController extends Controller
{
    public function __construct(
        private CoachAttendanceService $coachAttendanceService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['coach_id', 'session_id', 'attendance_date', 'start_date', 'end_date']);
        $attendances = $this->coachAttendanceService->getAllAttendances($filters);

        return response()->json($attendances);
    }

    public function show($id)
    {
        $attendance = $this->coachAttendanceService->getAttendanceById($id);

        if (!$attendance) {
            return response()->json(['message' => 'Coach attendance not found'], 404);
        }

        return response()->json($attendance);
    }

    public function store(StoreCoachAttendanceRequest $request)
    {
        $data = $request->validated();
        $data['recorded_by'] = $request->user()->id;
        
        $attendance = $this->coachAttendanceService->createAttendance($data);

        return response()->json($attendance, 201);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'actual_start_time' => 'nullable|date_format:H:i',
            'actual_end_time' => 'nullable|date_format:H:i|after:actual_start_time',
            'is_late' => 'nullable|boolean',
            'late_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
        ]);

        $attendance = $this->coachAttendanceService->updateAttendance($id, $request->only([
            'actual_start_time',
            'actual_end_time',
            'is_late',
            'late_minutes',
            'notes'
        ]));

        if (!$attendance) {
            return response()->json(['message' => 'Coach attendance not found'], 404);
        }

        return response()->json($attendance);
    }

    public function destroy($id)
    {
        $deleted = $this->coachAttendanceService->deleteAttendance($id);

        if (!$deleted) {
            return response()->json(['message' => 'Coach attendance not found'], 404);
        }

        return response()->json(['message' => 'Coach attendance deleted successfully']);
    }
}
