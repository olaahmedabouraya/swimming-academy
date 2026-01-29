<?php

namespace App\Http\Requests\Player;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'nullable|exists:branches,id',
            'level' => 'sometimes|in:beginner,intermediate,advanced,professional',
            'medical_notes' => 'nullable|string|max:1000',
            'emergency_contact' => 'nullable|string|max:500',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.exists' => 'Branch does not exist',
            'level.in' => 'Level must be beginner, intermediate, advanced, or professional',
        ];
    }
}


