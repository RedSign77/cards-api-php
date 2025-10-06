<?php

namespace App\Filament\Resources\CardTypeResource\Pages;

use App\Filament\Resources\CardTypeResource;
use App\Filament\Imports\CardTypeImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCardTypes extends ListRecords
{
    protected static string $resource = CardTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(CardTypeImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
