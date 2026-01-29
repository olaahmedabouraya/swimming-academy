<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'address' => 'sometimes|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'sometimes|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'capacity' => 'sometimes|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.max' => 'Branch name must not exceed 255 characters',
            'address.max' => 'Address must not exceed 500 characters',
            'phone.max' => 'Phone number must not exceed 20 characters',
            'email.email' => 'Email must be a valid email address',
            'capacity.integer' => 'Capacity must be an integer',
            'capacity.min' => 'Capacity must be at least 0',
        ];
    }
}


