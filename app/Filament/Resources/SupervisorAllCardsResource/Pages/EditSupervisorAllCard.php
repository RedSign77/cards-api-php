<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllCardsResource\Pages;

use App\Filament\Resources\SupervisorAllCardsResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllCard extends EditRecord
{
    protected static string $resource = SupervisorAllCardsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
