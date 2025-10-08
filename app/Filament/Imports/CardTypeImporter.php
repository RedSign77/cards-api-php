<?php

namespace App\Filament\Imports;

use App\Models\CardType;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CardTypeImporter extends Importer
{
    protected static ?string $model = CardType::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('name')
                ->requiredMapping()
                ->rules(['required', 'max:255']),
            ImportColumn::make('typetext')
                ->requiredMapping()
                ->rules(['required', 'max:255', 'alpha_dash']),
            ImportColumn::make('game_id')
                ->requiredMapping()
                ->numeric()
                ->rules(['required', 'exists:games,id']),
            ImportColumn::make('description')
                ->rules(['max:65535']),
        ];
    }

    public function resolveRecord(): ?CardType
    {
        // Ensure user_id is always set
        $this->data['user_id'] = auth()->id();

        return CardType::firstOrNew([
            'typetext' => $this->data['typetext'],
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
        $body = 'Your card type import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
