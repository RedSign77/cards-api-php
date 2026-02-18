<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\AiLogResource\Pages;

use App\Filament\Resources\AiLogResource;
use Filament\Resources\Pages\ListRecords;

class ListAiLogs extends ListRecords
{
    protected static string $resource = AiLogResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
