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
            $apiKey = config('services.mailjet.key');
            $apiSecret = config('services.mailjet.secret');

            // If credentials are not set, return log transport as fallback
            if (empty($apiKey) || empty($apiSecret)) {
                return Mail::createSymfonyTransport(['transport' => 'log']);
            }

            return new MailjetTransport($apiKey, $apiSecret);
        });
    }
}
