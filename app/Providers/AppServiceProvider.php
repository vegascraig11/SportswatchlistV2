<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\ServiceProvider;

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
        VerifyEmail::createUrlUsing(function ($notifiable) {
            $url = route(
                'verification.verify',
                [
                    'expires' => now()->addMinutes(config('auth.verification.expire', 60))->timestamp,
                    'email' => $notifiable->getEmailForVerification(),
                    'token' => $notifiable->verification_token,
                ]
            );

            return config('app.url') . '/login?verify_email=1&path=' . urlencode($url);
        });
    }
}
