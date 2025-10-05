<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Widgets;

use App\Filament\Resources\CardResource;
use App\Models\Card;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestCardsWidget extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = [
        'default' => 2,
        'sm' => 2,
        'md' => 2,
        'lg' => 2,
        'xl' => 2,
    ];

    public function table(Table $table): Table
    {
        return $table
            ->heading('My Latest Cards')
            ->query(
                Card::query()
                    ->where('user_id', auth()->id())
                    ->with(['game', 'cardType'])
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\ImageColumn::make('image')
                    ->label('Kép')
                    ->disk('public')
                    ->circular()
                    ->defaultImageUrl(url('/images/placeholder-card.svg')),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->weight('bold'),

                Tables\Columns\TextColumn::make('game.name')
                    ->label('Game')
                    ->badge()
                    ->color('primary'),

                Tables\Columns\TextColumn::make('cardType.name')
                    ->label('Type')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created at')
                    ->since()
                    ->sortable(),
            ])
            ->actions([
                Tables\Actions\Action::make('view')
                    ->url(fn (Card $record): string => CardResource::getUrl('edit', ['record' => $record]))
                    ->icon('heroicon-m-pencil-square'),
            ]);
    }
}
