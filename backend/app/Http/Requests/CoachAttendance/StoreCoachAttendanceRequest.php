<?php

namespace App\Http\Requests\CoachAttendance;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'coach_id' => 'required|exists:coaches,id',
            'session_id' => 'required|exists:training_sessions,id',
            'attendance_date' => 'required|date',
            'scheduled_start_time' => 'required|date_format:H:i',
            'scheduled_end_time' => 'required|date_format:H:i|after:scheduled_start_time',
            'actual_start_time' => 'nullable|date_format:H:i',
            'actual_end_time' => 'nullable|date_format:H:i|after:actual_start_time',
            'is_late' => 'nullable|boolean',
            'late_minutes' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
