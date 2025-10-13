<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\CompletedJobResource\Pages;

use App\Filament\Resources\CompletedJobResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCompletedJobs extends ListRecords
{
    protected static string $resource = CompletedJobResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('clear_all')
                ->label('Clear All')
                ->icon('heroicon-o-trash')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Clear All Completed Jobs')
                ->modalDescription('This will remove all completed jobs from the database. This action cannot be undone.')
                ->action(function () {
                    $count = \App\Models\CompletedJob::count();
                    \App\Models\CompletedJob::truncate();

                    \App\Models\SupervisorActivityLog::log(
                        action: 'clear_all_completed_jobs',
                        resourceType: 'CompletedJob',
                        resourceId: null,
                        description: "Cleared all {$count} completed job(s)",
                        metadata: [
                            'count' => $count,
                        ]
                    );

                    \Filament\Notifications\Notification::make()
                        ->title('All Jobs Cleared')
                        ->success()
                        ->body("{$count} completed job(s) have been removed.")
                        ->send();
                }),
        ];
    }
}
