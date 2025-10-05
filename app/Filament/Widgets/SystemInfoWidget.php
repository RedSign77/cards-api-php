<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SystemInfoWidget extends Widget
{
    protected static ?int $sort = 3;

    protected static string $view = 'filament.widgets.system-info-widget';

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

    public function getViewData(): array
    {
        return [
            'stats' => [
                [
                    'label' => 'Rendszer Verzió',
                    'value' => 'Laravel 12 + Filament 3',
                    'icon' => 'heroicon-o-check-circle',
                    'color' => 'green',
                ],
                [
                    'label' => 'Auth Method',
                    'value' => 'Sanctum Tokens',
                    'icon' => 'heroicon-o-shield-check',
                    'color' => 'purple',
                ],
                [
                    'label' => 'Database',
                    'value' => 'MySQL / SQLite',
                    'icon' => 'heroicon-o-circle-stack',
                    'color' => 'amber',
                ],
            ],
            'quickLinks' => [
                [
                    'label' => 'Összes kártya',
                    'url' => \App\Filament\Resources\CardResource::getUrl('index'),
                ],
                [
                    'label' => 'Játékok',
                    'url' => \App\Filament\Resources\GameResource::getUrl('index'),
                ],
                [
                    'label' => 'Felhasználók',
                    'url' => \App\Filament\Resources\UserResource::getUrl('index'),
                ],
            ],
        ];
    }
}
