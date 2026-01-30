<?php

namespace App\Http\Requests\Rating;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'technique_score' => 'sometimes|integer|min:0|max:100',
            'endurance_score' => 'sometimes|integer|min:0|max:100',
            'speed_score' => 'sometimes|integer|min:0|max:100',
            'attitude_score' => 'sometimes|integer|min:0|max:100',
            'comments' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'technique_score.integer' => 'Technique score must be an integer',
            'technique_score.min' => 'Technique score must be at least 0',
            'technique_score.max' => 'Technique score must not exceed 100',
            'endurance_score.integer' => 'Endurance score must be an integer',
            'endurance_score.min' => 'Endurance score must be at least 0',
            'endurance_score.max' => 'Endurance score must not exceed 100',
            'speed_score.integer' => 'Speed score must be an integer',
            'speed_score.min' => 'Speed score must be at least 0',
            'speed_score.max' => 'Speed score must not exceed 100',
            'attitude_score.integer' => 'Attitude score must be an integer',
            'attitude_score.min' => 'Attitude score must be at least 0',
            'attitude_score.max' => 'Attitude score must not exceed 100',
        ];
    }
}



