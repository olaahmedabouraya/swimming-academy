<?php

namespace App\Http\Requests\Schedule;

use Illuminate\Foundation\Http\FormRequest;

class UpdateScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'branch_id' => 'sometimes|exists:branches,id',
            'day_of_week' => 'sometimes|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'sometimes|date_format:H:i',
            'instructor_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
        ];

        // If end_time is provided, validate it against start_time if start_time is also provided
        if ($this->has('end_time')) {
            if ($this->has('start_time')) {
                $rules['end_time'] = 'required|date_format:H:i|after:start_time';
            } else {
                $rules['end_time'] = 'required|date_format:H:i';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'branch_id.exists' => 'Branch does not exist',
            'day_of_week.in' => 'Day of week must be a valid day',
            'start_time.date_format' => 'Start time must be in HH:mm format',
            'end_time.date_format' => 'End time must be in HH:mm format',
            'end_time.after' => 'End time must be after start time',
        ];
    }
}

