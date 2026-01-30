<?php

namespace App\Http\Requests\Rating;

use Illuminate\Foundation\Http\FormRequest;

class StoreRatingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'player_id' => 'required|exists:players,id',
            'technique_score' => 'required|integer|min:0|max:100',
            'endurance_score' => 'required|integer|min:0|max:100',
            'speed_score' => 'required|integer|min:0|max:100',
            'attitude_score' => 'required|integer|min:0|max:100',
            'comments' => 'nullable|string|max:1000',
            'rating_date' => 'required|date',
        ];
    }

    public function messages(): array
    {
        return [
            'player_id.required' => 'Player ID is required',
            'player_id.exists' => 'Player does not exist',
            'technique_score.required' => 'Technique score is required',
            'technique_score.integer' => 'Technique score must be an integer',
            'technique_score.min' => 'Technique score must be at least 0',
            'technique_score.max' => 'Technique score must not exceed 100',
            'endurance_score.required' => 'Endurance score is required',
            'endurance_score.integer' => 'Endurance score must be an integer',
            'endurance_score.min' => 'Endurance score must be at least 0',
            'endurance_score.max' => 'Endurance score must not exceed 100',
            'speed_score.required' => 'Speed score is required',
            'speed_score.integer' => 'Speed score must be an integer',
            'speed_score.min' => 'Speed score must be at least 0',
            'speed_score.max' => 'Speed score must not exceed 100',
            'attitude_score.required' => 'Attitude score is required',
            'attitude_score.integer' => 'Attitude score must be an integer',
            'attitude_score.min' => 'Attitude score must be at least 0',
            'attitude_score.max' => 'Attitude score must not exceed 100',
            'rating_date.required' => 'Rating date is required',
            'rating_date.date' => 'Rating date must be a valid date',
        ];
    }
}



