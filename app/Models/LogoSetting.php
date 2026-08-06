<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'header_logo',
        'footer_logo',
        'fav_icon',
    ];

    /**
     * Get the logo settings (singleton pattern).
     * Creates a record if it doesn't exist.
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'header_logo' => null,
                'footer_logo' => null,
                'fav_icon' => null,
            ]);
        }

        return $settings;
    }
}
