<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllGamesResource\Pages;

use App\Filament\Resources\SupervisorAllGamesResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllGame extends EditRecord
{
    protected static string $resource = SupervisorAllGamesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
