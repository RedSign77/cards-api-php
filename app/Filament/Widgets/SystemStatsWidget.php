<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\User;
use App\Models\Game;
use App\Models\Deck;
use App\Models\CardType;
use App\Models\Card;
use App\Models\Hexa;
use App\Models\Figure;

class SystemStatsWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-m-users')
                ->color('success'),

            Stat::make('Total Games', Game::count())
                ->description('Created games')
                ->descriptionIcon('heroicon-m-puzzle-piece')
                ->color('info'),

            Stat::make('Total Decks', Deck::count())
                ->description('Created decks')
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('warning'),

            Stat::make('Total Card Types', CardType::count())
                ->description('Defined card types')
                ->descriptionIcon('heroicon-m-tag')
                ->color('primary'),

            Stat::make('Total Cards', Card::count())
                ->description('Created cards')
                ->descriptionIcon('heroicon-m-rectangle-group')
                ->color('danger'),

            Stat::make('Total Hexas', Hexa::count())
                ->description('Created hexas')
                ->descriptionIcon('heroicon-m-cube')
                ->color('indigo'),

            Stat::make('Total Figures', Figure::count())
                ->description('Created figures')
                ->descriptionIcon('heroicon-m-user')
                ->color('purple'),
        ];
    }
}
