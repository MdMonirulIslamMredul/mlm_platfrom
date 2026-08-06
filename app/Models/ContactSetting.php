<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    protected $table = 'contact_settings';

    protected $fillable = [
        'contact_email',
        'contact_email_secondary',
        'contact_phone',
        'contact_address',
        'opening_hours',
        'facebook_url',
        'twitter_url',
        'youtube_url',
        'linkedin_url',
    ];

    /**
     * Get the contact settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'contact_email' => 'bmofinancialgurup@gmail.com',
                'contact_email_secondary' => 'magnainternationalcompany559@gmail.com',
                'contact_phone' => '+1 (555) 123-4567',
                'contact_address' => '550 FA Tower, William S Blvd 2000, IL, USA',
                'opening_hours' => '8.00 AM To 5.00 PM',
                'facebook_url' => '',
                'twitter_url' => '',
                'youtube_url' => '',
                'linkedin_url' => '',
            ]);
        }

        return $settings;
    }
}
