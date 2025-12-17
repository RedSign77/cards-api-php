<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllGamesResource\Pages;

use App\Filament\Resources\SupervisorAllGamesResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllGames extends ListRecords
{
    protected static string $resource = SupervisorAllGamesResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
