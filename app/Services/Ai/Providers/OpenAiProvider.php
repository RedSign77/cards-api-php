<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai\Providers;

use App\Services\Ai\AiProviderInterface;
use App\Services\Ai\AiResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpClient\HttpClient;

class OpenAiProvider implements AiProviderInterface
{
    private string $apiKey;
    private string $model;
    private string $baseUrl;
    private int $maxTokens;
    private float $temperature;
    private int $timeoutSeconds;

    public function __construct(
        string $apiKey,
        string $model = 'gpt-4o',
        int $maxTokens = 2048,
        float $temperature = 0.7,
    ) {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->baseUrl = config('ai.providers.openai.base_url', 'https://api.openai.com/v1');
        $this->maxTokens = $maxTokens;
        $this->temperature = $temperature;
        $this->timeoutSeconds = config('ai.timeout_seconds', 60);
    }

    /**
     * Send a chat message to OpenAI
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): AiResponse
    {
        $startTime = microtime(true);
        $model = $options['model'] ?? $this->model;
        $maxTokens = $options['max_tokens'] ?? $this->maxTokens;
        $temperature = $options['temperature'] ?? $this->temperature;
        $jsonMode = $options['json_mode'] ?? true;

        $payload = [
            'model' => $model,
            'messages' => [
                ['role' => 'system', 'content' => $systemPrompt],
                ['role' => 'user', 'content' => $userPrompt],
            ],
            'max_tokens' => $maxTokens,
            'temperature' => $temperature,
        ];

        if ($jsonMode) {
            $payload['response_format'] = ['type' => 'json_object'];
        }

        try {
            $client = HttpClient::create();
            $response = $client->request('POST', $this->baseUrl . '/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
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
            $content = $body['choices'][0]['message']['content'] ?? '';
            $usage = $body['usage'] ?? [];

            return AiResponse::success(
                content: $content,
                promptTokens: $usage['prompt_tokens'] ?? 0,
                completionTokens: $usage['completion_tokens'] ?? 0,
                model: $model,
                provider: $this->getProviderName(),
                durationMs: $durationMs,
            );

        } catch (\Exception $e) {
            $durationMs = (int) ((microtime(true) - $startTime) * 1000);
            Log::error('OpenAI API error', ['error' => $e->getMessage()]);

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
        return config('ai.providers.openai.models', []);
    }

    /**
     * Get provider name
     */
    public function getProviderName(): string
    {
        return 'openai';
    }
}
