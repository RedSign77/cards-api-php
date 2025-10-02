<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected int | string | array $columnSpan = [
        'default' => 3,
        'sm' => 1,
        'md' => 2,
        'lg' => 3,
        'xl' => 3,
    ];

    protected function getStats(): array
    {
        return [
            //
        ];
    }
}
