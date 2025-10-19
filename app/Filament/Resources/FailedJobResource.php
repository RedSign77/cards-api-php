<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\FailedJobResource\Pages;
use App\Models\FailedJob;
use App\Models\SupervisorActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class FailedJobResource extends Resource
{
    protected static ?string $model = FailedJob::class;

    protected static ?string $navigationIcon = 'heroicon-o-exclamation-triangle';

    protected static ?string $navigationLabel = 'Failed Jobs';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 12;

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $count = static::getModel()::count();
        return $count > 0 ? 'danger' : 'success';
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
                Tables\Columns\TextColumn::make('connection')
                    ->label('Connection')
                    ->badge()
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('queue')
                    ->label('Queue')
                    ->badge()
                    ->color('danger')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('exception')
                    ->label('Exception')
                    ->limit(100)
                    ->searchable()
                    ->wrap(),
                Tables\Columns\TextColumn::make('failed_at')
                    ->label('Failed At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('queue')
                    ->options(function () {
                        return FailedJob::query()
                            ->distinct()
                            ->pluck('queue', 'queue')
                            ->toArray();
                    }),
                Tables\Filters\SelectFilter::make('connection')
                    ->options(function () {
                        return FailedJob::query()
                            ->distinct()
                            ->pluck('connection', 'connection')
                            ->toArray();
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Failed Job #' . $record->id)
                    ->modalContent(fn ($record) => view('filament.resources.failed-job-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),
                Tables\Actions\Action::make('retry')
                    ->label('Retry')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (FailedJob $record) {
                        try {
                            Artisan::call('queue:retry', ['id' => $record->uuid]);

                            // Log supervisor action
                            SupervisorActivityLog::log(
                                action: 'retry_failed_job',
                                resourceType: 'FailedJob',
                                resourceId: $record->id,
                                description: "Retried failed job: {$record->uuid} from queue: {$record->queue}",
                                metadata: [
                                    'uuid' => $record->uuid,
                                    'queue' => $record->queue,
                                    'connection' => $record->connection,
                                ]
                            );

                            Notification::make()
                                ->title('Job Retried')
                                ->success()
                                ->body("Job {$record->uuid} has been queued for retry.")
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Retry Failed')
                                ->danger()
                                ->body($e->getMessage())
                                ->send();
                        }
                    }),
                Tables\Actions\DeleteAction::make()
                    ->label('Remove'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('retry_all')
                        ->label('Retry Selected')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->requiresConfirmation()
                        ->action(function ($records) {
                            $count = 0;
                            $uuids = [];
                            foreach ($records as $record) {
                                try {
                                    Artisan::call('queue:retry', ['id' => $record->uuid]);
                                    $count++;
                                    $uuids[] = $record->uuid;
                                } catch (\Exception $e) {
                                    // Continue with next record
                                }
                            }

                            // Log supervisor action
                            SupervisorActivityLog::log(
                                action: 'retry_failed_job',
                                resourceType: 'FailedJob',
                                resourceId: null,
                                description: "Bulk retried {$count} failed job(s)",
                                metadata: [
                                    'count' => $count,
                                    'uuids' => $uuids,
                                ]
                            );

                            Notification::make()
                                ->title('Jobs Retried')
                                ->success()
                                ->body("{$count} job(s) have been queued for retry.")
                                ->send();
                        }),
                    Tables\Actions\DeleteBulkAction::make()
                        ->label('Remove Selected'),
                ]),
            ])
            ->defaultSort('failed_at', 'desc')
            ->poll('10s');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFailedJobs::route('/'),
        ];
    }
}
