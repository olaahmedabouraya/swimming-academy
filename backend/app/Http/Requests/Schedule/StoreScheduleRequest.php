<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => 'required|exists:players,id',
            'branch_id' => 'required|exists:branches,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'instructor_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'player_id.required' => 'Player ID is required',
            'player_id.exists' => 'Player does not exist',
            'branch_id.required' => 'Branch ID is required',
            'branch_id.exists' => 'Branch does not exist',
            'day_of_week.required' => 'Day of week is required',
            'day_of_week.in' => 'Day of week must be a valid day',
            'start_time.required' => 'Start time is required',
            'start_time.date_format' => 'Start time must be in HH:mm format',
            'end_time.required' => 'End time is required',
            'end_time.date_format' => 'End time must be in HH:mm format',
            'end_time.after' => 'End time must be after start time',
        ];
    }
}


