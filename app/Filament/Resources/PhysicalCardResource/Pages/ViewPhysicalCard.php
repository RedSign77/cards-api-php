<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\PhysicalCardResource\Pages;

use App\Filament\Resources\PhysicalCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPhysicalCard extends ViewRecord
{
    protected static string $resource = PhysicalCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
