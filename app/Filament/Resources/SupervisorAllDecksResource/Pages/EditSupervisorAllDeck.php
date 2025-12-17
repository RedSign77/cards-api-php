<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllDecksResource\Pages;

use App\Filament\Resources\SupervisorAllDecksResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllDeck extends EditRecord
{
    protected static string $resource = SupervisorAllDecksResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
