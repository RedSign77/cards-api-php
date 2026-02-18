<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderInterface;
use App\Services\Ai\AiResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\HttpClient;

class AnthropicProvider implements AiProviderInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $maxTokens;
    private float $temperature;
    private int $timeoutSeconds;

    // Anthropic API version header
    private const API_VERSION = '2023-06-01';

    public function __construct(
        string $apiKey,
        string $model = 'claude-sonnet-4-6',
        int $maxTokens = 2048,
        float $temperature = 0.7,
    ) {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->baseUrl = config('ai.providers.anthropic.base_url', 'https://api.anthropic.com/v1');
        $this->maxTokens = $maxTokens;
        $this->temperature = $temperature;
        $this->timeoutSeconds = config('ai.timeout_seconds', 60);
    }

    /**
     * Send a chat message to Anthropic Claude
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): AiResponse
    {
        $startTime = microtime(true);
        $model = $options['model'] ?? $this->model;
        $maxTokens = $options['max_tokens'] ?? $this->maxTokens;
        $temperature = $options['temperature'] ?? $this->temperature;
        $jsonMode = $options['json_mode'] ?? true;

        // Anthropic uses system prompt at top level, not in messages array
        // For JSON mode, append instruction to system prompt
        $finalSystemPrompt = $systemPrompt;
        if ($jsonMode) {
            $finalSystemPrompt .= "\n\nIMPORTANT: Your response must be ONLY a valid JSON object. No markdown, no code blocks, no explanations outside the JSON.";
        }

        $payload = [
            'model' => $model,
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
            'system' => $finalSystemPrompt,
            'messages' => [
                ['role' => 'user', 'content' => $userPrompt],
            ],
        ];

        try {
            $client = HttpClient::create();
            $response = $client->request('POST', $this->baseUrl . '/messages', [
                'headers' => [
                    'x-api-key' => $this->apiKey,
                    'anthropic-version' => self::API_VERSION,
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload,
                'timeout' => $this->timeoutSeconds,
            ]);

            $statusCode = $response->getStatusCode();
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);

            if ($statusCode === 429) {
                return AiResponse::error(
                    errorMessage: 'Rate limit exceeded. Please try again later.',
                    model: $model,
                    provider: $this->getProviderName(),
                    durationMs: $durationMs,
                    status: 'rate_limited',
                );
            }

            if ($statusCode !== 200) {
                $body = $response->toArray(throw: false);
                $errorMsg = $body['error']['message'] ?? "HTTP {$statusCode} error";

                return AiResponse::error(
                    errorMessage: $errorMsg,
                    model: $model,
                    provider: $this->getProviderName(),
                    durationMs: $durationMs,
                );
            }

            $body = $response->toArray();

            // Anthropic response structure differs from OpenAI
            $content = '';
            if (isset($body['content']) && is_array($body['content'])) {
                foreach ($body['content'] as $block) {
                    if ($block['type'] === 'text') {
                        $content .= $block['text'];
                    }
                }
            }

            $usage = $body['usage'] ?? [];

            return AiResponse::success(
                content: trim($content),
                promptTokens: $usage['input_tokens'] ?? 0,
                completionTokens: $usage['output_tokens'] ?? 0,
                model: $model,
                provider: $this->getProviderName(),
                durationMs: $durationMs,
            );

        } catch (\Exception $e) {
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);
            Log::error('Anthropic API error', ['error' => $e->getMessage()]);

            return AiResponse::error(
                errorMessage: $e->getMessage(),
                model: $model,
                provider: $this->getProviderName(),
                durationMs: $durationMs,
            );
        }
    }

    /**
     * Test the connection with a minimal request
     */
    public function testConnection(): bool
    {
        $testPrompts = config('ai_prompts.connection_test');
        $response = $this->chat(
            systemPrompt: $testPrompts['system'],
            userPrompt: $testPrompts['user'],
            options: ['max_tokens' => 50, 'json_mode' => false],
        );

        return $response->isSuccess;
    }

    /**
     * Get available models
     */
    public function getAvailableModels(): array
    {
        return config('ai.providers.anthropic.models', []);
    }

    /**
     * Get provider name
     */
    public function getProviderName(): string
    {
        return 'anthropic';
    }
}
