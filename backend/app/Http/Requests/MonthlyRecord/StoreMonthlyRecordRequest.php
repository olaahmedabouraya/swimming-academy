<?php

namespace App\Http\Requests\MonthlyRecord;

use Illuminate\Foundation\Http\FormRequest;

class StoreMonthlyRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => 'required|exists:branches,id',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'revenue' => 'required|numeric|min:0',
            'new_enrollments' => 'required|integer|min:0',
            'total_active_players' => 'required|integer|min:0',
            'selling_rate' => 'required|numeric|min:0|max:100',
            'total_sessions_conducted' => 'required|integer|min:0',
            'total_attendance' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'branch_id.required' => 'Branch ID is required',
            'branch_id.exists' => 'Branch does not exist',
            'year.required' => 'Year is required',
            'year.integer' => 'Year must be an integer',
            'year.min' => 'Year must be at least 2000',
            'year.max' => 'Year must not exceed 2100',
            'month.required' => 'Month is required',
            'month.integer' => 'Month must be an integer',
            'month.min' => 'Month must be at least 1',
            'month.max' => 'Month must not exceed 12',
            'revenue.required' => 'Revenue is required',
            'revenue.numeric' => 'Revenue must be a number',
            'revenue.min' => 'Revenue must be at least 0',
            'new_enrollments.required' => 'New enrollments is required',
            'new_enrollments.integer' => 'New enrollments must be an integer',
            'new_enrollments.min' => 'New enrollments must be at least 0',
            'total_active_players.required' => 'Total active players is required',
            'total_active_players.integer' => 'Total active players must be an integer',
            'total_active_players.min' => 'Total active players must be at least 0',
            'selling_rate.required' => 'Selling rate is required',
            'selling_rate.numeric' => 'Selling rate must be a number',
            'selling_rate.min' => 'Selling rate must be at least 0',
            'selling_rate.max' => 'Selling rate must not exceed 100',
            'total_sessions_conducted.required' => 'Total sessions conducted is required',
            'total_sessions_conducted.integer' => 'Total sessions conducted must be an integer',
            'total_sessions_conducted.min' => 'Total sessions conducted must be at least 0',
            'total_attendance.required' => 'Total attendance is required',
            'total_attendance.integer' => 'Total attendance must be an integer',
            'total_attendance.min' => 'Total attendance must be at least 0',
        ];
    }
}



