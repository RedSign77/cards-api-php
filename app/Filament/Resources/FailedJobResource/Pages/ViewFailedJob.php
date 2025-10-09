<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\FailedJobResource\Pages;

use App\Filament\Resources\FailedJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Artisan;
use Filament\Notifications\Notification;

class ViewFailedJob extends ViewRecord
{
    protected static string $resource = FailedJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('retry')
                ->label('Retry Job')
                ->icon('heroicon-o-arrow-path')
                ->color('success')
                ->requiresConfirmation()
                ->action(function () {
                    try {
                        Artisan::call('queue:retry', ['id' => $this->record->uuid]);

                        Notification::make()
                            ->title('Job Retried')
                            ->success()
                            ->body("Job {$this->record->uuid} has been queued for retry.")
                            ->send();

                        return redirect()->route('filament.admin.resources.failed-jobs.index');
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Retry Failed')
                            ->danger()
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\DeleteAction::make()
                ->label('Remove Job'),
        ];
    }
}
