<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Providers\Filament;

use Filament\AvatarProviders\Contracts\AvatarProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Navigation\MenuItem;
use App\Filament\Pages\Dashboard;
use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Widgets\CustomAccountWidget;
use App\Filament\Widgets\SystemStatsWidget;
use App\Filament\Widgets\QuickLinksWidget;
use App\Filament\Widgets\StatsOverview;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->registration(Register::class)
            ->passwordReset()
            ->emailVerification()
            ->brandName('Cards Forge')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                CustomAccountWidget::class,
                QuickLinksWidget::class,
                SystemStatsWidget::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->shouldShowAvatarForm(
                        value: true,
                        directory: 'avatars',
                        rules: 'mimes:jpeg,png|max:2048'
                    )
                    ->shouldShowDeleteAccountForm(false)
                    ->shouldShowBrowserSessionsForm(false)
                    ->shouldShowSanctumTokens(false)
            ])
            ->userMenuItems([
                'profile' => MenuItem::make('profile')
                    ->label(fn() => auth()->user()->name)
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                'panels::body.end',
                fn () => view('filament.footer')
            );
    }
}
