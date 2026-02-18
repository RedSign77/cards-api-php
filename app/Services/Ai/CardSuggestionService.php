<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

use App\Exceptions\AiSuggestionException;
use App\Models\Card;
use App\Models\CardType;
use App\Models\Game;

class CardSuggestionService
{
    public function __construct(private AiProxyService $ai) {}

    /**
     * Generate a card suggestion based on game and card type context.
     *
     * @throws AiSuggestionException
     */
    public function suggest(int $gameId, int $cardTypeId, string $goal = ''): array
    {
        $game = Game::find($gameId);
        $cardType = CardType::find($cardTypeId);

        if (!$game || !$cardType) {
            throw new AiSuggestionException('Game or Card Type not found.');
        }

        $existingCards = Card::where('game_id', $gameId)
            ->where('type_id', $cardTypeId)
            ->latest()
            ->limit(5)
            ->get(['name', 'card_text', 'card_data']);

        $existingCardsContext = '';
        if ($existingCards->isNotEmpty()) {
            $lines = $existingCards->map(function ($card) {
                $fields = collect($card->card_data ?? [])->map(fn($f) => "{$f['fieldname']}: {$f['fieldvalue']}")->join(', ');
                return "- {$card->name}: {$card->card_text}" . ($fields ? " [{$fields}]" : '');
            })->join("\n");
            $existingCardsContext = "\n**Meglévő kártyák ebben a típusban (stílushoz)**:\n{$lines}";
        }

        $userPrompt = str_replace(
            ['{game_name}', '{card_type_name}', '{generation_goal}', '{existing_cards_context}'],
            [$game->name, $cardType->name, $goal ?: 'Kiegyensúlyozott, általános kártya', $existingCardsContext],
            config('ai_prompts.card_suggestion.user_template')
        );

        $response = $this->ai->chat(
            systemPrompt: config('ai_prompts.card_suggestion.system'),
            userPrompt: $userPrompt,
            options: ['json_mode' => true]
        );

        if (!$response->isSuccess) {
            throw new AiSuggestionException($response->errorMessage ?? 'AI request failed.');
        }

        $data = $response->toJsonArray();

        if (!$data || !isset($data['name'], $data['card_text'], $data['card_data'])) {
            throw new AiSuggestionException('AI returned an invalid card structure.');
        }

        return $data;
    }
}
