<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Notifications\VerifyEmail;

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
        Schema::defaultStringLength(191);

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // Force Carbon to use app timezone globally
        config(['app.timezone' => 'Asia/Phnom_Penh']);
        date_default_timezone_set(config('app.timezone'));

        // Customize the email verification URL
        VerifyEmail::createUrlUsing(function (object $notifiable) {
            $verificationUrl = URL::temporarySignedRoute(
                'email.verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return config('app.frontend_url') . '?verification_url=' . urlencode($verificationUrl);;
        });

    }
}
