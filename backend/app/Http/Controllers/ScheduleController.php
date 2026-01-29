<?php

namespace App\Http\Controllers;

use App\Http\Requests\Schedule\StoreScheduleRequest;
use App\Http\Requests\Schedule\UpdateScheduleRequest;
use App\Services\ScheduleService;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function __construct(
        private ScheduleService $scheduleService
    ) {}

    public function index(Request $request)
    {
        $filters = $request->only(['player_id', 'branch_id']);
        $schedules = $this->scheduleService->getAllSchedules($filters);

        return response()->json($schedules);
    }

    public function store(StoreScheduleRequest $request)
    {
        $schedule = $this->scheduleService->createSchedule($request->validated());

        return response()->json($schedule, 201);
    }

    public function update(UpdateScheduleRequest $request, $id)
    {
        $schedule = $this->scheduleService->updateSchedule($id, $request->validated());

        if (!$schedule) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        return response()->json($schedule);
    }

    public function destroy($id)
    {
        $deleted = $this->scheduleService->deleteSchedule($id);

        if (!$deleted) {
            return response()->json(['message' => 'Schedule not found'], 404);
        }

        return response()->json(['message' => 'Schedule deleted successfully']);
    }
}

