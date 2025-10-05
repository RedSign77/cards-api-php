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
            Stat::make('My Cards', Card::where('user_id', auth()->id())->count())
                ->description('Cards created by me')
                ->descriptionIcon('heroicon-m-credit-card')
                ->color('success')
                ->chart([7, 3, 4, 5, 6, 3, 5]),

            Stat::make('My Games', Game::where('creator_id', auth()->id())->count())
                ->description('Games I created')
                ->descriptionIcon('heroicon-m-puzzle-piece')
                ->color('info')
                ->chart([3, 2, 4, 3, 5, 4, 6]),

            Stat::make('My Decks', Deck::where('creator_id', auth()->id())->count())
                ->description('Decks I created')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning')
                ->chart([5, 4, 6, 5, 7, 6, 8]),

            Stat::make('Card Types', \App\Models\CardType::where('user_id', auth()->id())->count())
                ->description('Card types I created')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary')
                ->chart([2, 3, 2, 4, 3, 5, 4]),
        ];
    }
}
