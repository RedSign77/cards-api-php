<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class WelcomeWidget extends Widget
{
    protected static ?int $sort = 1;

    protected static string $view = 'filament.widgets.welcome-widget';

    protected int | string | array $columnSpan = [
        'default' => 3,
        'sm' => 1,
        'md' => 2,
        'lg' => 3,
        'xl' => 3,
    ];

    public function getViewData(): array
    {
        return [
            'userName' => auth()->user()->name,
            'features' => [
                [
                    'icon' => 'heroicon-o-credit-card',
                    'title' => 'Manage Cards',
                    'description' => 'Create and manage cards with dynamic fields and image uploads',
                ],
                [
                    'icon' => 'heroicon-o-puzzle-piece',
                    'title' => 'Game Systems',
                    'description' => 'Manage multiple games through a single interface',
                ],
                [
                    'icon' => 'heroicon-o-tag',
                    'title' => 'Card Type management',
                    'description' => 'Flexible card types with unique identifiers',
                ],
                [
                    'icon' => 'heroicon-o-rectangle-stack',
                    'title' => 'Deck Builder',
                    'description' => 'Create and manage custom decks',
                ],
                [
                    'icon' => 'heroicon-o-users',
                    'title' => 'User Management',
                    'description' => 'User management with Sanctum authentication',
                ],
                [
                    'icon' => 'heroicon-o-shield-check',
                    'title' => 'REST API',
                    'description' => 'Full API backend with token-based authentication',
                ],
            ],
        ];
    }
}
