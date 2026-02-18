<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */
declare(strict_types=1);

namespace App\Filament\Resources\DeckResource\Pages;

use App\Exceptions\AiSuggestionException;
use App\Filament\Resources\DeckResource;
use App\Services\Ai\DeckAnalyticsService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewDeck extends ViewRecord
{
    protected static string $resource = DeckResource::class;

    protected static ?string $title = 'View Deck';

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),

            Actions\Action::make('aiAnalytics')
                ->label('AI Analytics')
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->visible(fn () => auth()->user()?->supervisor === true)
                ->slideOver()
                ->modalHeading('Deck Synergy Analysis')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function () {
                    try {
                        $report = app(DeckAnalyticsService::class)->analyze($this->record->id);
                        return view('filament.modals.deck-analytics', ['report' => $report]);
                    } catch (AiSuggestionException $e) {
                        Notification::make()
                            ->danger()
                            ->title('AI Error')
                            ->body($e->getMessage())
                            ->send();
                        return view('filament.modals.deck-analytics-error', ['error' => $e->getMessage()]);
                    }
                }),
        ];
    }
}
