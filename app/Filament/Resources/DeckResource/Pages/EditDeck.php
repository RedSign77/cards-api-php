<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\DeckResource\Pages;

use App\Exceptions\AiSuggestionException;
use App\Filament\Resources\DeckResource;
use App\Models\Card;
use App\Models\Deck;
use App\Services\Ai\CardSwapService;
use App\Services\Ai\DeckAnalyticsService;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditDeck extends EditRecord
{
    protected static string $resource = DeckResource::class;

    protected function getHeaderActions(): array
    {
        return [
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

            Actions\Action::make('aiSwapSuggest')
                ->label('AI Card Swap')
                ->icon('heroicon-o-arrows-right-left')
                ->color('warning')
                ->visible(fn () => auth()->user()?->supervisor === true)
                ->slideOver()
                ->modalHeading('AI Card Swap Suggestions')
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close')
                ->modalContent(function () {
                    try {
                        $suggestions = app(CardSwapService::class)->suggest($this->record->id);
                        return view('filament.modals.deck-swap', [
                            'suggestions' => $suggestions,
                            'deckId'      => $this->record->id,
                        ]);
                    } catch (AiSuggestionException $e) {
                        Notification::make()
                            ->danger()
                            ->title('AI Error')
                            ->body($e->getMessage())
                            ->send();
                        return view('filament.modals.deck-analytics-error', ['error' => $e->getMessage()]);
                    }
                }),

            Actions\DeleteAction::make(),
        ];
    }

    /**
     * Livewire listener for applying a card swap from the modal view.
     */
    public function applyCardSwap(int $removeId, int $replaceWithId): void
    {
        $deck = $this->record;
        $deckData = $deck->deck_data ?? [];

        // Remove old card
        $deckData = array_values(array_filter($deckData, fn($item) => (int)($item['card_id'] ?? 0) !== $removeId));

        // Add new card with quantity 1 if not present
        $found = false;
        foreach ($deckData as &$item) {
            if ((int)($item['card_id'] ?? 0) === $replaceWithId) {
                $item['quantity'] = ($item['quantity'] ?? 1) + 1;
                $found = true;
                break;
            }
        }
        unset($item);

        if (!$found) {
            $deckData[] = ['card_id' => $replaceWithId, 'quantity' => 1];
        }

        $deck->deck_data = $deckData;
        $deck->save();

        $removedCard = Card::find($removeId);
        $addedCard   = Card::find($replaceWithId);

        $this->form->fill(array_merge($this->form->getState(), [
            'deck_data' => $deckData,
        ]));

        Notification::make()
            ->success()
            ->title('Card Swapped')
            ->body("Replaced \"{$removedCard?->name}\" with \"{$addedCard?->name}\".")
            ->send();
    }
}
