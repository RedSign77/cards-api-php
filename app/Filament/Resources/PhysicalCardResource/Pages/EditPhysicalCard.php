<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\PhysicalCardResource\Pages;

use App\Filament\Resources\PhysicalCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPhysicalCard extends EditRecord
{
    protected static string $resource = PhysicalCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
