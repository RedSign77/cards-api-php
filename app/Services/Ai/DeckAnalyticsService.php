<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

use App\Exceptions\AiSuggestionException;
use App\Models\Card;
use App\Models\Deck;

class DeckAnalyticsService
{
    public function __construct(private AiProxyService $ai) {}

    /**
     * Analyze a deck and return a structured synergy report.
     *
     * @throws AiSuggestionException
     */
    public function analyze(int $deckId): array
    {
        $deck = Deck::with('game')->find($deckId);

        if (!$deck) {
            throw new AiSuggestionException('Deck not found.');
        }

        $deckData = $deck->deck_data ?? [];

        if (empty($deckData)) {
            throw new AiSuggestionException('The deck has no cards to analyze.');
        }

        // Resolve all card IDs in the deck
        $cardIds = collect($deckData)->pluck('card_id')->filter()->unique()->values()->toArray();
        $cards = Card::whereIn('id', $cardIds)->get(['id', 'name', 'card_text', 'card_data']);

        if ($cards->isEmpty()) {
            throw new AiSuggestionException('No valid cards found in the deck.');
        }

        // Build deck list string
        $deckList = collect($deckData)->map(function ($item) use ($cards) {
            $card = $cards->firstWhere('id', $item['card_id']);
            if (!$card) return null;
            $qty = $item['quantity'] ?? 1;
            $fields = collect($card->card_data ?? [])->map(fn($f) => "{$f['fieldname']}: {$f['fieldvalue']}")->join(', ');
            return "- [{$qty}x] {$card->name}: {$card->card_text}" . ($fields ? " [{$fields}]" : '');
        })->filter()->join("\n");

        // Load top-10 alternatives from same game, not in deck
        $alternatives = Card::where('game_id', $deck->game_id)
            ->whereNotIn('id', $cardIds)
            ->get(['id', 'name', 'card_text', 'card_data'])
            ->sortByDesc(fn($c) => count($c->card_data ?? []))
            ->take(10);

        $alternativePool = $alternatives->map(function ($card) {
            $fields = collect($card->card_data ?? [])->map(fn($f) => "{$f['fieldname']}: {$f['fieldvalue']}")->join(', ');
            return "- {$card->name}: {$card->card_text}" . ($fields ? " [{$fields}]" : '');
        })->join("\n") ?: '(Nincs elérhető alternatív kártya)';

        // Game rules summary
        $gameRules = "Játék neve: {$deck->game->name}";

        $userPrompt = str_replace(
            ['{game_rules}', '{deck_list}', '{alternative_pool}'],
            [$gameRules, $deckList, $alternativePool],
            config('ai_prompts.deck_analytics.user_template')
        );

        $response = $this->ai->chat(
            systemPrompt: config('ai_prompts.deck_analytics.system'),
            userPrompt: $userPrompt,
            options: ['json_mode' => true]
        );

        if (!$response->isSuccess) {
            throw new AiSuggestionException($response->errorMessage ?? 'AI request failed.');
        }

        $data = $response->toJsonArray();

        if (!$data || !isset($data['overall_score'], $data['synergy_report'])) {
            throw new AiSuggestionException('AI returned an invalid analytics structure.');
        }

        return $data;
    }
}
