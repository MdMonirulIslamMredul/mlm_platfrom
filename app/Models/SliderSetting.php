<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'universal_slider',
    ];

    /**
     * Get the slider settings (singleton pattern).
     * Creates a record if it doesn't exist.
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'universal_slider' => null,
            ]);
        }

        return $settings;
    }
}
