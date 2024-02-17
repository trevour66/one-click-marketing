<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

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
       Validator::extend(
            'recaptcha',
            'App\\Validators\\ReCaptcha@validate'
        );

        Inertia::share([
            'google_recaptcha_config' => [
                'google_recaptcha_key' => config('recaptcha.key'),          
            ]
        ]);
    }
}
