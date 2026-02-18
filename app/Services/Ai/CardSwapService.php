<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

use App\Exceptions\AiSuggestionException;
use App\Models\Card;
use App\Models\Deck;

class CardSwapService
{
    public function __construct(private AiProxyService $ai) {}

    /**
     * Suggest card swaps for a deck, returning validated suggestions with card IDs.
     *
     * @throws AiSuggestionException
     */
    public function suggest(int $deckId): array
    {
        $deck = Deck::with('game')->find($deckId);

        if (!$deck) {
            throw new AiSuggestionException('Deck not found.');
        }

        $deckData = $deck->deck_data ?? [];

        if (empty($deckData)) {
            throw new AiSuggestionException('The deck has no cards.');
        }

        $cardIds = collect($deckData)->pluck('card_id')->filter()->unique()->values()->toArray();
        $deckCards = Card::whereIn('id', $cardIds)->get(['id', 'name', 'card_text', 'card_data']);

        // Load alternative cards from same game
        $alternatives = Card::where('game_id', $deck->game_id)
            ->whereNotIn('id', $cardIds)
            ->get(['id', 'name', 'card_text', 'card_data']);

        if ($alternatives->isEmpty()) {
            throw new AiSuggestionException('No alternative cards available in this game for swapping.');
        }

        $deckList = collect($deckData)->map(function ($item) use ($deckCards) {
            $card = $deckCards->firstWhere('id', $item['card_id']);
            if (!$card) return null;
            $qty = $item['quantity'] ?? 1;
            $fields = collect($card->card_data ?? [])->map(fn($f) => "{$f['fieldname']}: {$f['fieldvalue']}")->join(', ');
            return "- [{$qty}x] {$card->name}: {$card->card_text}" . ($fields ? " [{$fields}]" : '');
        })->filter()->join("\n");

        $alternativePool = $alternatives->map(function ($card) {
            $fields = collect($card->card_data ?? [])->map(fn($f) => "{$f['fieldname']}: {$f['fieldvalue']}")->join(', ');
            return "- {$card->name}: {$card->card_text}" . ($fields ? " [{$fields}]" : '');
        })->join("\n");

        $userPrompt = str_replace(
            ['{deck_list}', '{alternative_pool}'],
            [$deckList, $alternativePool],
            config('ai_prompts.card_swap.user_template')
        );

        $response = $this->ai->chat(
            systemPrompt: config('ai_prompts.card_swap.system'),
            userPrompt: $userPrompt,
            options: ['json_mode' => true]
        );

        if (!$response->isSuccess) {
            throw new AiSuggestionException($response->errorMessage ?? 'AI request failed.');
        }

        $raw = $response->toJsonArray();

        if (!is_array($raw) || empty($raw)) {
            throw new AiSuggestionException('AI returned no swap suggestions.');
        }

        // Enrich each suggestion with card IDs for UI use
        $suggestions = [];
        foreach ($raw as $item) {
            if (!isset($item['remove_name'], $item['replace_with_name'], $item['reason'])) {
                continue;
            }

            $removeCard = $deckCards->first(fn($c) => strcasecmp($c->name, $item['remove_name']) === 0);
            $replaceCard = $alternatives->first(fn($c) => strcasecmp($c->name, $item['replace_with_name']) === 0);

            if (!$removeCard || !$replaceCard) {
                continue; // skip invalid suggestions
            }

            $suggestions[] = [
                'remove_id'        => $removeCard->id,
                'remove_name'      => $removeCard->name,
                'replace_with_id'  => $replaceCard->id,
                'replace_with_name'=> $replaceCard->name,
                'reason'           => $item['reason'],
            ];
        }

        if (empty($suggestions)) {
            throw new AiSuggestionException('AI suggestions could not be matched to existing cards.');
        }

        return $suggestions;
    }
}
