<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class StorePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'branch_id' => 'nullable|exists:branches,id',
            'level' => 'required|in:beginner,intermediate,advanced,professional',
            'enrollment_date' => 'required|date',
            'medical_notes' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'User does not exist',
            'branch_id.exists' => 'Branch does not exist',
            'level.required' => 'Level is required',
            'level.in' => 'Level must be beginner, intermediate, advanced, or professional',
            'enrollment_date.required' => 'Enrollment date is required',
            'enrollment_date.date' => 'Enrollment date must be a valid date',
        ];
    }
}


