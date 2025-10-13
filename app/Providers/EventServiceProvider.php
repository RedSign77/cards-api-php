<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Providers;

use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Verified;
use Illuminate\Queue\Events\JobProcessed;
use App\Listeners\LogUserLogin;
use App\Listeners\LogUserLogout;
use App\Listeners\LogUserRegistered;
use App\Listeners\LogPasswordReset;
use App\Listeners\LogFailedLogin;
use App\Listeners\LogEmailVerified;
use App\Listeners\LogCompletedJob;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Login::class => [
            LogUserLogin::class,
        ],
        Logout::class => [
            LogUserLogout::class,
        ],
        Registered::class => [
            LogUserRegistered::class,
        ],
        PasswordReset::class => [
            LogPasswordReset::class,
        ],
        Failed::class => [
            LogFailedLogin::class,
        ],
        Verified::class => [
            LogEmailVerified::class,
        ],
        JobProcessed::class => [
            LogCompletedJob::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
