<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;

class TermsAndConditions extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.terms-and-conditions';

    protected static ?string $navigationLabel = 'Terms & Conditions';

    protected static ?string $title = 'Terms and Conditions';

    protected static ?string $navigationGroup = null;

    protected static ?int $navigationSort = 999;

    public static function shouldRegisterNavigation(): bool
    {
        return false; // Don't show in navigation menu
    }

    public static function getSlug(): string
    {
        return 'terms-and-conditions';
    }
}
