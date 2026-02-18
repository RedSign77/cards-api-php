<?php

namespace App\Providers;

use App\Models\PhysicalCard;
use App\Observers\PhysicalCardObserver;
use App\Services\Ai\AiProxyService;
use App\Services\Ai\AiProviderInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the AI Proxy Service as a singleton
        $this->app->singleton(AiProxyService::class, function ($app) {
            return new AiProxyService();
        });

        // Bind the interface to the resolved provider via the proxy service
        $this->app->bind(AiProviderInterface::class, function ($app) {
            return $app->make(AiProxyService::class)->getProvider();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        PhysicalCard::observe(PhysicalCardObserver::class);
    }
}
