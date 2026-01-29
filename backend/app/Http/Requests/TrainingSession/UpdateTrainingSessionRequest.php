<?php

namespace App\Http\Requests\TrainingSession;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTrainingSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'sometimes|exists:branches,id',
            'group' => 'nullable|integer|min:1',
            'day_of_week' => 'sometimes|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'nullable|boolean',
            'max_capacity' => 'nullable|integer|min:1',
            'notes' => 'nullable|string|max:1000',
        ];
    }
}
