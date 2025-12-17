<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\SupervisorAllFiguresResource\Pages;

use App\Filament\Resources\SupervisorAllFiguresResource;
use Filament\Resources\Pages\ListRecords;

class ListSupervisorAllFigures extends ListRecords
{
    protected static string $resource = SupervisorAllFiguresResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
