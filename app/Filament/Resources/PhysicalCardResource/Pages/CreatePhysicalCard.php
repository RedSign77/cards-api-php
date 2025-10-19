<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\PhysicalCardResource\Pages;

use App\Filament\Resources\PhysicalCardResource;
use App\Jobs\AutoEvaluateCard;
use App\Models\PhysicalCard;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreatePhysicalCard extends CreateRecord
{
    protected static string $resource = PhysicalCardResource::class;

    protected function afterCreate(): void
    {
        // Dispatch the auto-evaluation job
        AutoEvaluateCard::dispatch($this->record);

        // Show notification to user
        Notification::make()
            ->title('Card listing created successfully')
            ->body('Your card is now under review. You will be notified once the evaluation is complete.')
            ->success()
            ->send();
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return null; // We're using a custom notification
    }
}
