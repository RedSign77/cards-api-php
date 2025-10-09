<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\FailedJobResource\Pages;

use App\Filament\Resources\FailedJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class ListFailedJobs extends ListRecords
{
    protected static string $resource = FailedJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('retry_all')
                ->label('Retry All Failed')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Retry All Failed Jobs')
                ->modalDescription('Are you sure you want to retry all failed jobs?')
                ->action(function () {
                    try {
                        Artisan::call('queue:retry', ['id' => 'all']);

                        Notification::make()
                            ->title('All Jobs Retried')
                            ->success()
                            ->body('All failed jobs have been queued for retry.')
                            ->send();

                        $this->dispatch('$refresh');
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Retry Failed')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\Action::make('flush_all')
                ->label('Clear All Failed')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Failed Jobs')
                ->modalDescription('Are you sure you want to permanently delete all failed jobs? This action cannot be undone.')
                ->action(function () {
                    try {
                        Artisan::call('queue:flush');

                        Notification::make()
                            ->title('All Failed Jobs Cleared')
                            ->success()
                            ->body('All failed jobs have been permanently deleted.')
                            ->send();

                        $this->dispatch('$refresh');
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Clear Failed')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->color('gray')
                ->action(fn () => $this->dispatch('$refresh')),
        ];
    }
}
