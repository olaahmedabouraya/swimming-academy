<?php

namespace App\Http\Requests\Attendance;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAttendanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'status' => 'sometimes|in:present,absent,late,excused',
            'check_in_time' => 'nullable|date_format:H:i',
            'notes' => 'nullable|string|max:1000',
        ];

        // If check_out_time is provided, validate it against check_in_time if check_in_time is also provided
        if ($this->has('check_out_time')) {
            if ($this->has('check_in_time') && $this->check_in_time) {
                $rules['check_out_time'] = 'required|date_format:H:i|after:check_in_time';
            } else {
                $rules['check_out_time'] = 'required|date_format:H:i';
            }
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'status.in' => 'Status must be present, absent, late, or excused',
            'check_in_time.date_format' => 'Check-in time must be in HH:mm format',
            'check_out_time.date_format' => 'Check-out time must be in HH:mm format',
            'check_out_time.after' => 'Check-out time must be after check-in time',
        ];
    }
}
