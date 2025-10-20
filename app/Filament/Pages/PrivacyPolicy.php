<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;

class PrivacyPolicy extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static string $view = 'filament.pages.privacy-policy';

    protected static ?string $navigationLabel = 'Privacy Policy';

    protected static ?string $title = 'Privacy Policy';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 998;

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Don't show in navigation menu
    }

    public static function getSlug(): string
    {
        return 'privacy-policy';
    }
}
