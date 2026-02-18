<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Resources\CardResource\Pages;

use App\Exceptions\AiSuggestionException;
use App\Filament\Resources\CardResource;
use App\Models\CardType;
use App\Models\Game;
use App\Services\Ai\AbilityRerollService;
use App\Services\Ai\CardSuggestionService;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditCard extends EditRecord
{
    protected static string $resource = CardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('aiSuggest')
                ->label('AI Suggest')
                ->icon('heroicon-o-sparkles')
                ->color('warning')
                ->visible(fn () => auth()->user()?->supervisor === true)
                ->form([
                    Forms\Components\Select::make('game_id')
                        ->label('Game')
                        ->options(fn () => Game::where('creator_id', auth()->id())->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->default(fn () => $this->form->getState()['game_id'] ?? null),
                    Forms\Components\Select::make('type_id')
                        ->label('Card Type')
                        ->options(fn () => CardType::where('user_id', auth()->id())->pluck('name', 'id'))
                        ->required()
                        ->searchable()
                        ->default(fn () => $this->form->getState()['type_id'] ?? null),
                    Forms\Components\TextInput::make('generation_goal')
                        ->label('Generation Goal')
                        ->placeholder('e.g. Aggressive creature with low cost')
                        ->maxLength(255),
                ])
                ->action(function (array $data) {
                    try {
                        $result = app(CardSuggestionService::class)->suggest(
                            gameId: (int) $data['game_id'],
                            cardTypeId: (int) $data['type_id'],
                            goal: $data['generation_goal'] ?? ''
                        );

                        $this->form->fill([
                            'game_id'   => $data['game_id'],
                            'type_id'   => $data['type_id'],
                            'name'      => $result['name'] ?? '',
                            'card_text' => $result['card_text'] ?? ($result['lore_text'] ?? ''),
                            'card_data' => $result['card_data'] ?? [],
                        ]);

                        Notification::make()
                            ->success()
                            ->title('AI Suggestion Applied')
                            ->body('Review the generated fields and save when ready.')
                            ->send();
                    } catch (AiSuggestionException $e) {
                        Notification::make()
                            ->danger()
                            ->title('AI Error')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Actions\Action::make('rerollAbility')
                ->label('Re-roll Ability')
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->visible(fn () => auth()->user()?->supervisor === true)
                ->form([
                    Forms\Components\Select::make('tone')
                        ->label('Tone')
                        ->options([
                            'Aggressive' => 'Aggressive',
                            'Defensive'  => 'Defensive',
                            'Support'    => 'Support',
                            'Balanced'   => 'Balanced',
                        ])
                        ->required()
                        ->default('Balanced'),
                ])
                ->action(function (array $data) {
                    try {
                        $result = app(AbilityRerollService::class)->reroll(
                            cardId: $this->record->id,
                            tone: $data['tone']
                        );

                        $currentState = $this->form->getState();

                        $this->form->fill(array_merge($currentState, [
                            'card_text' => $result['card_text'] ?? $currentState['card_text'],
                            'card_data' => $result['card_data'] ?? $currentState['card_data'],
                        ]));

                        if ($result['_truncated'] ?? false) {
                            Notification::make()
                                ->warning()
                                ->title('Ability Re-rolled (Truncated)')
                                ->body('The generated text exceeded the max length and was truncated.')
                                ->send();
                        } else {
                            Notification::make()
                                ->success()
                                ->title('Ability Re-rolled')
                                ->body('Card ability updated. Save to persist the changes.')
                                ->send();
                        }
                    } catch (AiSuggestionException $e) {
                        Notification::make()
                            ->danger()
                            ->title('AI Error')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
