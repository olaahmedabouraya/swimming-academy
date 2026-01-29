<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

class StoreBranchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'manager_name' => 'nullable|string|max:255',
            'capacity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Branch name is required',
            'name.max' => 'Branch name must not exceed 255 characters',
            'address.required' => 'Address is required',
            'address.max' => 'Address must not exceed 500 characters',
            'phone.required' => 'Phone number is required',
            'phone.max' => 'Phone number must not exceed 20 characters',
            'email.email' => 'Email must be a valid email address',
            'capacity.required' => 'Capacity is required',
            'capacity.integer' => 'Capacity must be an integer',
            'capacity.min' => 'Capacity must be at least 0',
        ];
    }
}


