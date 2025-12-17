<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllCardsResource\Pages;

use App\Filament\Resources\SupervisorAllCardsResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllCards extends ListRecords
{
    protected static string $resource = SupervisorAllCardsResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
