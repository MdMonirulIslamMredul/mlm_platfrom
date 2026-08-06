<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    protected $table = 'general_settings';

    protected $fillable = [
        'site_name',
        'site_email',
        'site_phone',
        'site_address',
        'footer_text',
    ];

    /**
     * Get the general settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'site_name' => 'Canada Visa Processing',
                'site_email' => 'info@canadavisa.com',
                'site_phone' => '+1 (555) 123-4567',
                'site_address' => '550 FA Tower, William S Blvd 2000, IL, USA',
                'footer_text' => '© 2025 Canada Visa Processing. All rights reserved.',
            ]);
        }

        return $settings;
    }
}
