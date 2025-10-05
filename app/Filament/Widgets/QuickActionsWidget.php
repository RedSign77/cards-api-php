<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Filament\Resources\CardResource;
use App\Filament\Resources\CardTypeResource;
use App\Filament\Resources\DeckResource;
use App\Filament\Resources\GameResource;
use Filament\Widgets\Widget;

class QuickActionsWidget extends Widget
{
    protected static ?int $sort = 0;

    protected static string $view = 'filament.widgets.quick-actions-widget';

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

    public function getActions(): array
    {
        return [
            [
                'label' => 'Create Card',
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'success',
                'url' => CardResource::getUrl('create'),
                'description' => 'Create a new card',
            ],
            [
                'label' => 'New Game',
                'icon' => 'heroicon-o-puzzle-piece',
                'color' => 'primary',
                'url' => GameResource::getUrl('create'),
                'description' => 'Create a new game',
            ],
            [
                'label' => 'New Type',
                'icon' => 'heroicon-o-tag',
                'color' => 'info',
                'url' => CardTypeResource::getUrl('create'),
                'description' => 'Create a new card type',
            ],
            [
                'label' => 'New Deck',
                'icon' => 'heroicon-o-rectangle-stack',
                'color' => 'warning',
                'url' => DeckResource::getUrl('create'),
                'description' => 'Create a new deck',
            ],
        ];
    }
}
