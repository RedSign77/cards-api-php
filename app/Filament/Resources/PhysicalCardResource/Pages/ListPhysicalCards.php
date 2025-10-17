<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\PhysicalCardResource\Pages;

use App\Filament\Resources\PhysicalCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPhysicalCards extends ListRecords
{
    protected static string $resource = PhysicalCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
