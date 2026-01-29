<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description'
    ];

    public static function get(string $key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        return match($setting->type) {
            'date' => $setting->value,
            'number' => (float) $setting->value,
            'boolean' => (bool) $setting->value,
            default => $setting->value,
        };
    }

    public static function set(string $key, $value, string $type = 'string', string $description = null): self
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'description' => $description
            ]
        );

        return $setting;
    }
}
