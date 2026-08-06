<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\ContactSetting;
use App\Models\LogoSetting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share settings with all layout views
        View::composer([
            'frontend.layouts.header',
            'frontend.layouts.footer',
            'frontend.layouts.app',
            'layouts.header',
            'layouts.footer',
            'layouts.app'
        ], function ($view) {
            $view->with('generalSettings', \App\Models\GeneralSetting::getSettings());
            $view->with('logoSettings', \App\Models\LogoSetting::getSettings());
            $view->with('contactSettings', \App\Models\ContactSetting::getSettings());
        });
    }
}
