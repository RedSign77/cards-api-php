<?php

namespace App\Filament\Imports;

use App\Models\Card;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CardImporter extends Importer
{
    protected static ?string $model = Card::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('game_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'exists:games,id']),
            ImportColumn::make('type_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'exists:card_types,id']),
            ImportColumn::make('card_text')
                ->rules(['max:65535']),
            ImportColumn::make('image')
                ->rules(['max:255']),
            ImportColumn::make('card_data')
                ->castStateUsing(function (string $state): ?array {
                    if (empty($state)) {
                        return null;
                    }
                    $decoded = json_decode($state, true);
                    return is_array($decoded) ? $decoded : null;
                }),
        ];
    }

    public function resolveRecord(): ?Card
    {
        // Ensure user_id is always set
        $this->data['user_id'] = auth()->id();

        return Card::firstOrNew([
            'name' => $this->data['name'],
            'user_id' => auth()->id(),
        ]);
    }

    protected function beforeFill(): void
    {
        // Ensure user_id is set before filling the model
        $this->data['user_id'] = auth()->id();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your card import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
