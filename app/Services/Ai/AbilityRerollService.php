<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

use App\Exceptions\AiSuggestionException;
use App\Models\Card;

class AbilityRerollService
{
    public function __construct(private AiProxyService $ai) {}

    /**
     * Re-generate card_text and card_data for an existing card with a given tone.
     *
     * @throws AiSuggestionException
     */
    public function reroll(int $cardId, string $tone): array
    {
        $card = Card::find($cardId);

        if (!$card) {
            throw new AiSuggestionException('Card not found.');
        }

        $maxLength = (int) config('ai.ability_max_length', 500);

        $currentCardData = collect($card->card_data ?? [])
            ->map(fn($f) => "- {$f['fieldname']}: {$f['fieldvalue']}")
            ->join("\n");

        $userPrompt = str_replace(
            ['{card_name}', '{current_description}', '{tone}', '{max_length}', '{current_card_data}'],
            [$card->name, $card->card_text ?? '(nincs leírás)', $tone, $maxLength, $currentCardData ?: '(nincs statisztika)'],
            config('ai_prompts.ability_reroll.user_template')
        );

        // Inject max_length into system prompt
        $systemPrompt = str_replace('{max_length}', $maxLength, config('ai_prompts.ability_reroll.system'));

        $response = $this->ai->chat(
            systemPrompt: $systemPrompt,
            userPrompt: $userPrompt,
            options: ['json_mode' => true]
        );

        if (!$response->isSuccess) {
            throw new AiSuggestionException($response->errorMessage ?? 'AI request failed.');
        }

        $data = $response->toJsonArray();

        if (!$data || !isset($data['card_text'], $data['card_data'])) {
            throw new AiSuggestionException('AI returned an invalid ability structure.');
        }

        // Truncate if over limit
        $truncated = false;
        if (strlen($data['card_text']) > $maxLength) {
            $data['card_text'] = substr($data['card_text'], 0, $maxLength);
            $truncated = true;
        }

        $data['_truncated'] = $truncated;

        return $data;
    }
}
