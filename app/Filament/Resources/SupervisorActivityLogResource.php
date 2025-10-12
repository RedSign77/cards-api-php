<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\SupervisorActivityLogResource\Pages;
use App\Models\SupervisorActivityLog;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SupervisorActivityLogResource extends Resource
{
    protected static ?string $model = SupervisorActivityLog::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'Supervisor Activity Logs';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 11;

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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->label('Supervisor')
                    ->searchable()
                    ->sortable()
                    ->default('N/A'),

                Tables\Columns\TextColumn::make('action')
                    ->label('Action')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approve_user' => 'success',
                        'run_job' => 'info',
                        'retry_failed_job' => 'warning',
                        'delete_job', 'delete_failed_job' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => str_replace('_', ' ', ucwords($state, '_')))
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('resource_type')
                    ->label('Resource')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('resource_id')
                    ->label('Resource ID')
                    ->searchable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->limit(50)
                    ->searchable()
                    ->wrap(),

                Tables\Columns\TextColumn::make('ip_address')
                    ->label('IP Address')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date & Time')
                    ->dateTime('Y-m-d H:i:s')
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('action')
                    ->options([
                        'approve_user' => 'Approve User',
                        'run_job' => 'Run Job',
                        'retry_failed_job' => 'Retry Failed Job',
                        'delete_job' => 'Delete Job',
                        'delete_failed_job' => 'Delete Failed Job',
                    ]),
                Tables\Filters\SelectFilter::make('supervisor')
                    ->relationship('supervisor', 'name'),
                Tables\Filters\SelectFilter::make('resource_type')
                    ->options(function () {
                        return SupervisorActivityLog::query()
                            ->distinct()
                            ->whereNotNull('resource_type')
                            ->pluck('resource_type', 'resource_type')
                            ->toArray();
                    }),
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
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Supervisor Activity Log #' . $record->id)
                    ->modalContent(fn ($record) => view('filament.resources.supervisor-activity-log-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
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
            'index' => Pages\ListSupervisorActivityLogs::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::whereDate('created_at', today())->count();
    }
}
