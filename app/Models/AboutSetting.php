<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    protected $table = 'about_settings';

    protected $fillable = [
        'about_title',
        'about_subtitle',
        'about_description',
        'mission_statement',
        'vision_statement',
        'about_image_1',
        'about_image_2',
        'about_image_3',
        'about_image_4',
    ];

    /**
     * Get the about settings instance (singleton pattern)
     */
    public static function getSettings()
    {
        $settings = self::first();

        if (!$settings) {
            $settings = self::create([
                'about_title' => 'About Canada Visa Processing',
                'about_subtitle' => 'We Are Dedicated To Shaping The Dreams Of Individuals',
                'about_description' => 'With our unwavering commitment to excellence and a team of experienced immigration experts, we have established ourselves as a reliable and trusted partner in the field of immigration.

Our company was born out of a passion for helping people achieve their dreams of living and working in Canada. Over the years, we have grown into a dynamic and forward-thinking immigration consultancy, serving clients from all corners of the globe.',
                'mission_statement' => 'To provide exceptional immigration services that help individuals and families achieve their dreams of living and working in Canada.',
                'vision_statement' => 'To be the most trusted and reliable immigration consultancy, recognized for our commitment to excellence and client satisfaction.',
            ]);
        }

        return $settings;
    }
}
