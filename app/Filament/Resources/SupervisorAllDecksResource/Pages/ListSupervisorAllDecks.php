<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllDecksResource\Pages;

use App\Filament\Resources\SupervisorAllDecksResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllDecks extends ListRecords
{
    protected static string $resource = SupervisorAllDecksResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
