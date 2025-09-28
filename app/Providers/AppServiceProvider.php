<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Profile sidebar জন্য View Composer
        View::composer('partials.profile-brief', function ($view) {
            $profile = Auth::user();

            $profileCompletion = 0;
            if ($profile->profile_photo_url) $profileCompletion += 25;
            if ($profile->phone_verified) $profileCompletion += 25;
            if ($profile->bio) $profileCompletion += 25;
            if ($profile->documents_verified) $profileCompletion += 25;

            $view->with(compact('profile', 'profileCompletion'));
        });
    }
}
