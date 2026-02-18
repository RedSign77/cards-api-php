<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\AiLogResource\Pages;
use App\Models\AiLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AiLogResource extends Resource
{
    protected static ?string $model = AiLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';

    protected static ?string $navigationLabel = 'AI Logs';

    protected static ?string $navigationGroup = 'AI Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'AI Log';

    protected static ?string $pluralModelLabel = 'AI Logs';

    /**
     * Only supervisors can access this resource.
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isSupervisor();
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    /**
     * Navigation badge showing total log count.
     */
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form->schema([]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Request Details')
                    ->schema([
                        TextEntry::make('user.name')
                            ->label('User')
                            ->default('System'),
                        TextEntry::make('provider')
                            ->label('Provider')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'openai' => 'success',
                                'anthropic' => 'info',
                                default => 'gray',
                            }),
                        TextEntry::make('model')
                            ->label('Model'),
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'success' => 'success',
                                'error' => 'danger',
                                'rate_limited' => 'warning',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->label('Timestamp')
                            ->dateTime(),
                    ])
                    ->columns(3),

                Section::make('Token Usage & Performance')
                    ->schema([
                        TextEntry::make('prompt_tokens')
                            ->label('Prompt Tokens')
                            ->numeric(),
                        TextEntry::make('completion_tokens')
                            ->label('Completion Tokens')
                            ->numeric(),
                        TextEntry::make('total_tokens')
                            ->label('Total Tokens')
                            ->numeric(),
                        TextEntry::make('duration_ms')
                            ->label('Duration (ms)')
                            ->numeric(),
                        TextEntry::make('cost_estimate')
                            ->label('Est. Cost (USD)')
                            ->money('USD')
                            ->default('N/A'),
                    ])
                    ->columns(3),

                Section::make('Prompts')
                    ->schema([
                        TextEntry::make('system_prompt')
                            ->label('System Prompt')
                            ->prose()
                            ->columnSpanFull(),
                        TextEntry::make('user_prompt')
                            ->label('User Prompt')
                            ->prose()
                            ->columnSpanFull(),
                    ]),

                Section::make('Response')
                    ->schema([
                        TextEntry::make('response')
                            ->label('AI Response')
                            ->prose()
                            ->columnSpanFull()
                            ->default('No response (error occurred)'),
                        TextEntry::make('error_message')
                            ->label('Error Message')
                            ->visible(fn ($record) => !empty($record->error_message))
                            ->color('danger')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->default('System'),

                Tables\Columns\TextColumn::make('provider')
                    ->label('Provider')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'openai' => 'success',
                        'anthropic' => 'info',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('model')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'success' => 'success',
                        'error' => 'danger',
                        'rate_limited' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_tokens')
                    ->label('Tokens')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('cost_estimate')
                    ->label('Est. Cost')
                    ->money('USD')
                    ->sortable()
                    ->default('—'),

                Tables\Columns\TextColumn::make('duration_ms')
                    ->label('Duration')
                    ->formatStateUsing(fn ($state) => $state < 1000 ? "{$state}ms" : round($state / 1000, 2) . 's')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'success' => 'Success',
                        'error' => 'Error',
                        'rate_limited' => 'Rate Limited',
                    ]),

                Tables\Filters\SelectFilter::make('provider')
                    ->options([
                        'openai' => 'OpenAI',
                        'anthropic' => 'Anthropic',
                    ]),

                Tables\Filters\SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->label('User'),

                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from')
                            ->label('From'),
                        Forms\Components\DatePicker::make('created_until')
                            ->label('Until'),
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
                Tables\Actions\ViewAction::make()
                    ->slideOver()
                    ->modalWidth('4xl'),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAiLogs::route('/'),
        ];
    }
}
