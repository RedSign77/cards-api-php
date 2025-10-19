<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\CompletedJobResource\Pages;
use App\Models\CompletedJob;
use App\Models\SupervisorActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CompletedJobResource extends Resource
{
    protected static ?string $model = CompletedJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-check-circle';

    protected static ?string $navigationLabel = 'Completed Jobs';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 11;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\TextColumn::make('uuid')
                    ->label('UUID')
                    ->limit(20)
                    ->copyable()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('job_class')
                    ->label('Job Type')
                    ->searchable()
                    ->formatStateUsing(fn ($state) => class_basename($state))
                    ->badge()
                    ->color('success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('connection')
                    ->label('Connection')
                    ->badge()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('queue')
                    ->label('Queue')
                    ->badge()
                    ->color('success')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('attempts')
                    ->label('Attempts')
                    ->badge()
                    ->color(fn ($state) => $state > 1 ? 'warning' : 'success')
                    ->sortable(),
                Tables\Columns\TextColumn::make('execution_time')
                    ->label('Execution Time')
                    ->formatStateUsing(fn (CompletedJob $record) => $record->execution_time_human)
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('completed_at')
                    ->label('Completed At')
                    ->dateTime()
                    ->sortable()
                    ->since()
                    ->description(fn ($record) => $record->completed_at->format('Y-m-d H:i:s')),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('queue')
                    ->options(function () {
                        return CompletedJob::query()
                            ->distinct()
                            ->pluck('queue', 'queue')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('connection')
                    ->options(function () {
                        return CompletedJob::query()
                            ->distinct()
                            ->pluck('connection', 'connection')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('job_class')
                    ->label('Job Type')
                    ->options(function () {
                        return CompletedJob::query()
                            ->whereNotNull('job_class')
                            ->distinct()
                            ->pluck('job_class', 'job_class')
                            ->map(fn ($item) => class_basename($item))
                            ->toArray();
                    }),
                Tables\Filters\Filter::make('completed_today')
                    ->label('Completed Today')
                    ->query(fn ($query) => $query->whereDate('completed_at', today())),
                Tables\Filters\Filter::make('completed_week')
                    ->label('Completed This Week')
                    ->query(fn ($query) => $query->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Completed Job #' . $record->id)
                    ->modalContent(fn ($record) => view('filament.resources.completed-job-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove')
                    ->action(function (CompletedJob $record) {
                        // Log supervisor action
                        SupervisorActivityLog::log(
                            action: 'delete_completed_job',
                            resourceType: 'CompletedJob',
                            resourceId: $record->id,
                            description: "Deleted completed job: {$record->uuid} from queue: {$record->queue}",
                            metadata: [
                                'uuid' => $record->uuid,
                                'queue' => $record->queue,
                                'connection' => $record->connection,
                                'job_class' => $record->job_class,
                            ]
                        );

                        $record->delete();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Selected')
                        ->action(function ($records) {
                            $count = $records->count();
                            $uuids = $records->pluck('uuid')->toArray();

                            // Log supervisor action
                            SupervisorActivityLog::log(
                                action: 'bulk_delete_completed_jobs',
                                resourceType: 'CompletedJob',
                                resourceId: null,
                                description: "Bulk deleted {$count} completed job(s)",
                                metadata: [
                                    'count' => $count,
                                    'uuids' => $uuids,
                                ]
                            );

                            $records->each->delete();

                            Notification::make()
                                ->title('Jobs Removed')
                                ->success()
                                ->body("{$count} completed job(s) have been removed.")
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('clear_old')
                        ->label('Clear Old Jobs (>7 days)')
                        ->icon('heroicon-o-trash')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading('Clear Old Completed Jobs')
                        ->modalDescription('This will remove all completed jobs older than 7 days. This action cannot be undone.')
                        ->action(function () {
                            $count = CompletedJob::where('completed_at', '<', now()->subDays(7))->count();
                            CompletedJob::where('completed_at', '<', now()->subDays(7))->delete();

                            // Log supervisor action
                            SupervisorActivityLog::log(
                                action: 'clear_old_completed_jobs',
                                resourceType: 'CompletedJob',
                                resourceId: null,
                                description: "Cleared {$count} completed job(s) older than 7 days",
                                metadata: [
                                    'count' => $count,
                                ]
                            );

                            Notification::make()
                                ->title('Old Jobs Cleared')
                                ->success()
                                ->body("{$count} old completed job(s) have been removed.")
                                ->send();
                        }),
                ]),
            ])
            ->defaultSort('completed_at', 'desc')
            ->poll('10s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCompletedJobs::route('/'),
        ];
    }
}
