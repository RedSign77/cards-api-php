<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources;

use App\Filament\Resources\JobResource\Pages;
use App\Models\Job;
use App\Models\SupervisorActivityLog;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Artisan;

class JobResource extends Resource
{
    protected static ?string $model = Job::class;

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    protected static ?string $navigationLabel = 'Queue Jobs';

    protected static ?string $navigationGroup = 'System Settings';

    protected static ?int $navigationSort = 10;

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
                Tables\Actions\Action::make('run')
                    ->label('Run Now')
                    ->icon('heroicon-o-play')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Run Queue Job')
                    ->modalDescription(fn (Job $record) => "Are you sure you want to manually execute this job? Queue: {$record->queue}")
                    ->modalSubmitActionLabel('Yes, run it')
                    ->action(function (Job $record) {
                        try {
                            // Simply run queue:work for one job
                            // This is the safest way to execute a queued job
                            $exitCode = Artisan::call('queue:work', [
                                '--once' => true,
                                '--queue' => $record->queue,
                                '--stop-when-empty' => true,
                            ]);

                            if ($exitCode === 0) {
                                // Log supervisor action
                                SupervisorActivityLog::log(
                                    action: 'run_job',
                                    resourceType: 'Job',
                                    resourceId: $record->id,
                                    description: "Manually executed job from queue: {$record->queue}",
                                    metadata: [
                                        'queue' => $record->queue,
                                        'job_id' => $record->id,
                                    ]
                                );

                                \Filament\Notifications\Notification::make()
                                    ->title('Job executed successfully')
                                    ->success()
                                    ->body('The job has been processed.')
                                    ->send();
                            } else {
                                throw new \Exception('Queue worker returned non-zero exit code: ' . $exitCode);
                            }
                        } catch (\Exception $e) {
                            \Filament\Notifications\Notification::make()
                                ->title('Failed to run job')
                                ->danger()
                                ->body('Error: ' . $e->getMessage())
                                ->duration(10000)
                                ->send();

                            // Log the error for debugging
                            \Log::error('Manual job execution failed', [
                                'job_id' => $record->id,
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                        }
                    })
                    ->visible(fn (Job $record) => $record->reserved_at === null),

                Tables\Actions\ViewAction::make()
                    ->modalHeading(fn ($record) => 'Queue Job #' . $record->id)
                    ->modalContent(fn ($record) => view('filament.resources.job-view', ['record' => $record]))
                    ->modalWidth('xl')
                    ->slideOver(),

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
        ];
    }
}
