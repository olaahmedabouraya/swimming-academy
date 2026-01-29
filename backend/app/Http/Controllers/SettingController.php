<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->mapWithKeys(function ($setting) {
            return [$setting->key => [
                'value' => $setting->value,
                'type' => $setting->type,
                'description' => $setting->description
            ]];
        });

        return response()->json($settings);
    }

    public function get(string $key)
    {
        $value = Setting::get($key);
        return response()->json(['key' => $key, 'value' => $value]);
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $setting = Setting::where('key', $key)->first();
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $setting->value = $request->value;
        $setting->save();

        return response()->json([
            'key' => $setting->key,
            'value' => $setting->value,
            'type' => $setting->type,
            'description' => $setting->description
        ]);
    }

    public function updatePeriodDates(Request $request)
    {
        $request->validate([
            'period_start_date' => 'required|date',
            'period_end_date' => 'required|date|after:period_start_date',
        ]);

        Setting::set('period_start_date', $request->period_start_date, 'date', 'Start date of the billing/enrollment period');
        Setting::set('period_end_date', $request->period_end_date, 'date', 'End date of the billing/enrollment period');

        return response()->json([
            'message' => 'Period dates updated successfully',
            'period_start_date' => $request->period_start_date,
            'period_end_date' => $request->period_end_date
        ]);
    }
}
