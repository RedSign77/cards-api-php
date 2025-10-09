<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Queue Jobs';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 11;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        return $count > 0 ? 'warning' : 'success';
    }

    public static function canAccess(): bool
    {
        return auth()->user()->isSupervisor();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Job Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('id')
                            ->label('Job ID'),
                        Infolists\Components\TextEntry::make('queue')
                            ->label('Queue')
                            ->badge(),
                        Infolists\Components\TextEntry::make('attempts')
                            ->label('Attempts')
                            ->badge()
                            ->color(fn (int $state): string => match (true) {
                                $state === 0 => 'success',
                                $state < 3 => 'warning',
                                default => 'danger',
                            }),
                        Infolists\Components\TextEntry::make('available_at')
                            ->label('Available At')
                            ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : 'N/A'),
                        Infolists\Components\TextEntry::make('reserved_at')
                            ->label('Reserved At')
                            ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : 'Not Reserved'),
                        Infolists\Components\TextEntry::make('created_at')
                            ->label('Created At')
                            ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : 'N/A'),
                    ])
                    ->columns(3),
                Infolists\Components\Section::make('Payload')
                    ->schema([
                        Infolists\Components\TextEntry::make('payload')
                            ->label('')
                            ->formatStateUsing(function ($state) {
                                if (is_array($state)) {
                                    return json_encode($state, JSON_PRETTY_PRINT);
                                }
                                if (is_string($state)) {
                                    $decoded = json_decode($state, true);
                                    return $decoded ? json_encode($decoded, JSON_PRETTY_PRINT) : $state;
                                }
                                return 'N/A';
                            })
                            ->copyable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('queue')
                    ->label('Queue')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attempts')
                    ->label('Attempts')
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state === 0 => 'success',
                        $state < 3 => 'warning',
                        default => 'danger',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('available_at')
                    ->label('Available At')
                    ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : 'N/A')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reserved_at')
                    ->label('Reserved At')
                    ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : '-')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->formatStateUsing(fn ($state) => $state ? date('Y-m-d H:i:s', $state) : 'N/A')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('queue')
                    ->options(function () {
                        return Job::query()
                            ->distinct()
                            ->pluck('queue', 'queue')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Selected'),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('10s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListJobs::route('/'),
            'view' => Pages\ViewJob::route('/{record}'),
        ];
    }
}
