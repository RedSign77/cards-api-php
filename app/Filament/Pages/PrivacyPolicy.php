<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Redirect;

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

    public function mount(): void
    {
        // Redirect to public page in new window via JavaScript
        $this->js('window.open("' . route('privacy') . '", "_blank")');
    }
}
