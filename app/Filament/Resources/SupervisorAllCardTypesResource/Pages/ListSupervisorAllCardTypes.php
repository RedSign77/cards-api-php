<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllCardTypesResource\Pages;

use App\Filament\Resources\SupervisorAllCardTypesResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllCardTypes extends ListRecords
{
    protected static string $resource = SupervisorAllCardTypesResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
