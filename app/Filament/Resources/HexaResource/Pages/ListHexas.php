<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\HexaResource\Pages;

use App\Filament\Resources\HexaResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHexas extends ListRecords
{
    protected static string $resource = HexaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
