<?php

namespace App\Filament\Resources\DeckResource\Pages;

use App\Filament\Resources\DeckResource;
use App\Filament\Imports\DeckImporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDecks extends ListRecords
{
    protected static string $resource = DeckResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ImportAction::make()
                ->importer(DeckImporter::class),
            Actions\CreateAction::make(),
        ];
    }
}
