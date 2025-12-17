<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllCardTypesResource\Pages;

use App\Filament\Resources\SupervisorAllCardTypesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllCardType extends EditRecord
{
    protected static string $resource = SupervisorAllCardTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
