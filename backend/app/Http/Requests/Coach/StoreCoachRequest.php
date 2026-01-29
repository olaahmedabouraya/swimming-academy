<?php

namespace App\Http\Requests\Coach;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachRequest extends FormRequest
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
            'specialization' => 'nullable|string|max:255',
            'hourly_rate' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ];
    }
}
