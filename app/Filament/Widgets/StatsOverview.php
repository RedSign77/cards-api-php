<?php

namespace App\Filament\Widgets;

use App\Models\Card;
use App\Models\Deck;
use App\Models\Game;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = [
        'default' => 1,
        'sm' => 1,
        'md' => 1,
        'lg' => 1,
        'xl' => 1,
    ];

    protected function getStats(): array
    {
        return [
            Stat::make('Total Cards', Card::count())
                ->description('All cards in the system')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('Active Games', Game::count())
                ->description('Games available')
                ->descriptionIcon('heroicon-m-puzzle-piece')
                ->color('info')
                ->chart([3, 2, 4, 3, 5, 4, 6]),

            Stat::make('Total Decks', Deck::count())
                ->description('Decks created')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning')
                ->chart([5, 4, 6, 5, 7, 6, 8]),

            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary')
                ->chart([2, 3, 2, 4, 3, 5, 4]),
        ];
    }
}
