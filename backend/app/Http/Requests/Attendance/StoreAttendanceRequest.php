<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => 'required|exists:players,id',
            'schedule_id' => 'nullable|exists:schedules,id',
            'session_id' => 'nullable|exists:training_sessions,id',
            'attendance_date' => 'required|date',
            'status' => 'required|in:present,absent,late,excused',
            'check_in_time' => 'nullable|date_format:H:i',
            'check_out_time' => 'nullable|date_format:H:i|after:check_in_time',
            'actual_start_time' => 'nullable|date_format:H:i',
            'actual_end_time' => 'nullable|date_format:H:i|after:actual_start_time',
            'coach_notes' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'player_id.required' => 'Player ID is required',
            'player_id.exists' => 'Player does not exist',
            'schedule_id.exists' => 'Schedule does not exist',
            'session_id.exists' => 'Training session does not exist',
            'attendance_date.required' => 'Attendance date is required',
            'attendance_date.date' => 'Attendance date must be a valid date',
            'status.required' => 'Status is required',
            'status.in' => 'Status must be present, absent, late, or excused',
            'check_in_time.date_format' => 'Check-in time must be in HH:mm format',
            'check_out_time.date_format' => 'Check-out time must be in HH:mm format',
            'check_out_time.after' => 'Check-out time must be after check-in time',
        ];
    }
}
