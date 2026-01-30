<?php

namespace App\Http\Requests\MonthlyRecord;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMonthlyRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'revenue' => 'sometimes|numeric|min:0',
            'new_enrollments' => 'sometimes|integer|min:0',
            'total_active_players' => 'sometimes|integer|min:0',
            'selling_rate' => 'sometimes|numeric|min:0|max:100',
            'total_sessions_conducted' => 'sometimes|integer|min:0',
            'total_attendance' => 'sometimes|integer|min:0',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'revenue.numeric' => 'Revenue must be a number',
            'revenue.min' => 'Revenue must be at least 0',
            'new_enrollments.integer' => 'New enrollments must be an integer',
            'new_enrollments.min' => 'New enrollments must be at least 0',
            'total_active_players.integer' => 'Total active players must be an integer',
            'total_active_players.min' => 'Total active players must be at least 0',
            'selling_rate.numeric' => 'Selling rate must be a number',
            'selling_rate.min' => 'Selling rate must be at least 0',
            'selling_rate.max' => 'Selling rate must not exceed 100',
            'total_sessions_conducted.integer' => 'Total sessions conducted must be an integer',
            'total_sessions_conducted.min' => 'Total sessions conducted must be at least 0',
            'total_attendance.integer' => 'Total attendance must be an integer',
            'total_attendance.min' => 'Total attendance must be at least 0',
        ];
    }
}



