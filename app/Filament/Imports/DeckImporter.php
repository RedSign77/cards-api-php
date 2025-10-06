<?php

namespace App\Filament\Imports;

use App\Models\Deck;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class DeckImporter extends Importer
{
    protected static ?string $model = Deck::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('deck_name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('game_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'exists:games,id']),
            ImportColumn::make('deck_description')
                ->rules(['max:65535']),
            ImportColumn::make('deck_data')
                ->castStateUsing(function (string $state): ?array {
                    if (empty($state)) {
                        return null;
                    }
                    $decoded = json_decode($state, true);
                    return is_array($decoded) ? $decoded : null;
                }),
        ];
    }

    public function resolveRecord(): ?Deck
    {
        return Deck::firstOrNew([
            'deck_name' => $this->data['deck_name'],
            'creator_id' => auth()->id(),
        ]);
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your deck import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
