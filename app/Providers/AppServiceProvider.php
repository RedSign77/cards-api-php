<?php

namespace App\Providers;

use App\Mail\MailjetTransport;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

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
        Mail::extend('mailjet', function (array $config) {
            return new MailjetTransport(
                config('services.mailjet.key'),
                config('services.mailjet.secret')
            );
        });
    }
}
