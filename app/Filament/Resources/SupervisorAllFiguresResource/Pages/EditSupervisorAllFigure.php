<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllFiguresResource\Pages;

use App\Filament\Resources\SupervisorAllFiguresResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSupervisorAllFigure extends EditRecord
{
    protected static string $resource = SupervisorAllFiguresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
