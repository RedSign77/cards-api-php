<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\UserActivityLogResource\Pages;
use App\Models\UserActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class UserActivityLogResource extends Resource
{
    protected static ?string $model = UserActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    protected static ?string $navigationLabel = 'User Activity Logs';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    public static function canAccess(): bool
    {
        return auth()->user()->isSupervisor();
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Activity Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('user.name')
                            ->label('User')
                            ->icon('heroicon-m-user'),
                        Infolists\Components\TextEntry::make('event_type')
                            ->label('Event Type')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'login' => 'success',
                                'logout' => 'warning',
                                default => 'gray',
                            })
                            ->icon(fn (string $state): string => match ($state) {
                                'login' => 'heroicon-m-arrow-right-on-rectangle',
                                'logout' => 'heroicon-m-arrow-left-on-rectangle',
                                default => 'heroicon-m-question-mark-circle',
                            }),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Date & Time')
                            ->dateTime()
                            ->icon('heroicon-m-clock'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Technical Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('ip_address')
                            ->label('IP Address')
                            ->icon('heroicon-m-globe-alt')
                            ->copyable(),
                        Infolists\Components\TextEntry::make('user_agent')
                            ->label('User Agent')
                            ->icon('heroicon-m-computer-desktop')
                            ->copyable()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('event_type')
                    ->label('Event')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'login' => 'success',
                        'logout' => 'warning',
                        default => 'gray',
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user_agent')
                    ->label('User Agent')
                    ->limit(50)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime()
                    ->sortable()
                    ->default(true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('event_type')
                    ->options([
                        'login' => 'Login',
                        'logout' => 'Logout',
                    ]),
                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserActivityLogs::route('/'),
            'view' => Pages\ViewUserActivityLog::route('/{record}'),
        ];
    }
}
