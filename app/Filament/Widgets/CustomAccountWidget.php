<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class CustomAccountWidget extends Widget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    protected int | string | array $columnSpan = 2;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.custom-account-widget';

    public function getUserStats(): array
    {
        $user = auth()->user();

        return [
            'games' => $user->games()->count(),
            'decks' => $user->decks()->count(),
            'cardTypes' => $user->cardTypes()->count(),
            'cards' => $user->cards()->count(),
        ];
    }
}
