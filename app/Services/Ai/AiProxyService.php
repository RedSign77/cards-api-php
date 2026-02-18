<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

use App\Models\AiLog;
use App\Models\AiSetting;
use App\Services\Ai\Providers\AnthropicProvider;
use App\Services\Ai\Providers\OpenAiProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AiProxyService
{
    private AiProviderInterface $provider;
    private int $retryAttempts;
    private int $retryDelayMs;

    public function __construct()
    {
        $this->retryAttempts = (int) config('ai.retry_attempts', 3);
        $this->retryDelayMs = (int) config('ai.retry_delay_ms', 1000);
        $this->provider = $this->resolveProvider();
    }

    /**
     * Send a chat request through the configured AI provider.
     * Handles retry logic, logging, and error normalization.
     */
    public function chat(string $systemPrompt, string $userPrompt, array $options = []): AiResponse
    {
        $attempt = 0;
        $lastResponse = null;

        while ($attempt < $this->retryAttempts) {
            $attempt++;

            $response = $this->provider->chat($systemPrompt, $userPrompt, $options);
            $lastResponse = $response;

            // Log the request
            $this->logRequest($systemPrompt, $userPrompt, $response);

            if ($response->isSuccess) {
                return $response;
            }

            // Retry on rate limit with exponential backoff
            if ($response->status === 'rate_limited' && $attempt < $this->retryAttempts) {
                $delay = $this->retryDelayMs * (2 ** ($attempt - 1)); // exponential: 1s, 2s, 4s
                Log::warning("AI rate limited, retrying in {$delay}ms (attempt {$attempt}/{$this->retryAttempts})");
                usleep($delay * 1000);
                continue;
            }

            // Don't retry on other errors
            break;
        }

        return $lastResponse;
    }

    /**
     * Test the current provider's connection.
     * Returns a result array with 'success' bool and 'message' string.
     *
     * @return array{success: bool, message: string, status: string}
     */
    public function testConnection(): array
    {
        $testPrompts = config('ai_prompts.connection_test');

        $response = $this->chat(
            systemPrompt: $testPrompts['system'],
            userPrompt: $testPrompts['user'],
            options: ['max_tokens' => 50, 'json_mode' => false],
        );

        if ($response->isSuccess) {
            return ['success' => true, 'message' => 'Connection successful.', 'status' => 'success'];
        }

        if ($response->status === 'rate_limited') {
            return ['success' => false, 'message' => 'API key is valid but rate limit reached. Try again in a moment.', 'status' => 'rate_limited'];
        }

        return ['success' => false, 'message' => $response->errorMessage ?? 'Unknown error.', 'status' => 'error'];
    }

    /**
     * Get the current provider instance.
     */
    public function getProvider(): AiProviderInterface
    {
        return $this->provider;
    }

    /**
     * Get available models for the current provider.
     */
    public function getAvailableModels(): array
    {
        return $this->provider->getAvailableModels();
    }

    /**
     * Resolve the AI provider from settings or config.
     */
    private function resolveProvider(): AiProviderInterface
    {
        $providerName = AiSetting::get('ai_provider') ?? config('ai.default_provider', 'openai');
        $apiKey = AiSetting::get('ai_api_key') ?? '';
        $model = AiSetting::get('ai_model') ?? null;
        $maxTokens = (int) (AiSetting::get('ai_max_tokens') ?? config('ai.default_max_tokens', 2048));
        $temperature = (float) (AiSetting::get('ai_temperature') ?? config('ai.default_temperature', 0.7));

        return match ($providerName) {
            'anthropic' => new AnthropicProvider(
                apiKey: $apiKey,
                model: $model ?? config('ai.providers.anthropic.default_model', 'claude-sonnet-4-6'),
                maxTokens: $maxTokens,
                temperature: $temperature,
            ),
            default => new OpenAiProvider(
                apiKey: $apiKey,
                model: $model ?? config('ai.providers.openai.default_model', 'gpt-4o'),
                maxTokens: $maxTokens,
                temperature: $temperature,
            ),
        };
    }

    /**
     * Log the AI request to the database.
     */
    private function logRequest(string $systemPrompt, string $userPrompt, AiResponse $response): void
    {
        if (!config('ai.log_requests', true)) {
            return;
        }

        try {
            $costEstimate = $this->calculateCost(
                provider: $response->provider,
                model: $response->model,
                promptTokens: $response->promptTokens,
                completionTokens: $response->completionTokens,
            );

            AiLog::create([
                'user_id' => Auth::id(),
                'provider' => $response->provider,
                'model' => $response->model,
                'prompt_tokens' => $response->promptTokens,
                'completion_tokens' => $response->completionTokens,
                'total_tokens' => $response->totalTokens,
                'system_prompt' => $systemPrompt,
                'user_prompt' => $userPrompt,
                'response' => $response->isSuccess ? $response->content : null,
                'status' => $response->status,
                'error_message' => $response->errorMessage,
                'duration_ms' => $response->durationMs,
                'cost_estimate' => $costEstimate,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to log AI request', ['error' => $e->getMessage()]);
        }
    }

    /**
     * Calculate estimated cost based on token usage.
     */
    private function calculateCost(string $provider, string $model, int $promptTokens, int $completionTokens): ?float
    {
        $inputCosts = config("ai.providers.{$provider}.cost_per_1k_input", []);
        $outputCosts = config("ai.providers.{$provider}.cost_per_1k_output", []);

        if (!isset($inputCosts[$model]) || !isset($outputCosts[$model])) {
            return null;
        }

        $inputCost = ($promptTokens / 1000) * $inputCosts[$model];
        $outputCost = ($completionTokens / 1000) * $outputCosts[$model];

        return round($inputCost + $outputCost, 6);
    }
}
