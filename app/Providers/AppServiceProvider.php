<?php

namespace App\Providers;

use App\Models\PhysicalCard;
use App\Observers\PhysicalCardObserver;
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
        PhysicalCard::observe(PhysicalCardObserver::class);
    }
}
