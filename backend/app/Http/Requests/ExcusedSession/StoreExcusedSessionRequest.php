<?php

namespace App\Http\Requests\ExcusedSession;

use Illuminate\Foundation\Http\FormRequest;

class StoreExcusedSessionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => 'required|exists:players,id',
            'original_attendance_id' => 'nullable|exists:attendances,id',
            'original_session_id' => 'nullable|exists:training_sessions,id',
            'original_date' => 'required|date',
            'excuse_reason' => 'required|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'player_id.required' => 'Player ID is required',
            'player_id.exists' => 'Player does not exist',
            'original_date.required' => 'Original date is required',
            'original_date.date' => 'Original date must be a valid date',
            'excuse_reason.required' => 'Excuse reason is required',
            'excuse_reason.max' => 'Excuse reason cannot exceed 1000 characters',
        ];
    }
}
