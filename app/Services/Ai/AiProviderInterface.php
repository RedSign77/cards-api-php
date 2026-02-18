<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

interface AiProviderInterface
{
    /**
     * Send a chat message to the AI provider.
     *
     * @param  string  $systemPrompt  The system-level instructions for the AI
     * @param  string  $userPrompt  The user's specific request
     * @param  array  $options  Optional overrides: max_tokens, temperature, json_mode
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): AiResponse;

    /**
     * Test the connection to the AI provider with a minimal request.
     */
    public function testConnection(): bool;

    /**
     * Get available models for this provider.
     *
     * @return array<string, string> key-value: model_id => model_label
     */
    public function getAvailableModels(): array;

    /**
     * Get the provider identifier string.
     */
    public function getProviderName(): string;
}
