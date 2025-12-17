<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllHexasResource\Pages;

use App\Filament\Resources\SupervisorAllHexasResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllHexa extends EditRecord
{
    protected static string $resource = SupervisorAllHexasResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
