<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllHexasResource\Pages;

use App\Filament\Resources\SupervisorAllHexasResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllHexas extends ListRecords
{
    protected static string $resource = SupervisorAllHexasResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
