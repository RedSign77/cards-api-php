<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Services\Ai;

class AiResponse
{
    public function __construct(
        public readonly string $content,
        public readonly int $promptTokens,
        public readonly int $completionTokens,
        public readonly int $totalTokens,
        public readonly string $model,
        public readonly string $provider,
        public readonly int $durationMs,
        public readonly bool $isSuccess,
        public readonly ?string $errorMessage = null,
        public readonly string $status = 'success', // success, error, rate_limited
    ) {}

    /**
     * Create a successful response
     */
    public static function success(
        string $content,
        int $promptTokens,
        int $completionTokens,
        string $model,
        string $provider,
        int $durationMs,
    ): self {
        return new self(
            content: $content,
            promptTokens: $promptTokens,
            completionTokens: $completionTokens,
            totalTokens: $promptTokens + $completionTokens,
            model: $model,
            provider: $provider,
            durationMs: $durationMs,
            isSuccess: true,
            status: 'success',
        );
    }

    /**
     * Create an error response
     */
    public static function error(
        string $errorMessage,
        string $model,
        string $provider,
        int $durationMs,
        string $status = 'error',
    ): self {
        return new self(
            content: '',
            promptTokens: 0,
            completionTokens: 0,
            totalTokens: 0,
            model: $model,
            provider: $provider,
            durationMs: $durationMs,
            isSuccess: false,
            errorMessage: $errorMessage,
            status: $status,
        );
    }

    /**
     * Convert to array for storage/logging
     */
    public function toArray(): array
    {
        return [
            'content' => $this->content,
            'prompt_tokens' => $this->promptTokens,
            'completion_tokens' => $this->completionTokens,
            'total_tokens' => $this->totalTokens,
            'model' => $this->model,
            'provider' => $this->provider,
            'duration_ms' => $this->durationMs,
            'is_success' => $this->isSuccess,
            'error_message' => $this->errorMessage,
            'status' => $this->status,
        ];
    }

    /**
     * Parse content as JSON and return as array.
     * Returns null if content is not valid JSON.
     */
    public function toJsonArray(): ?array
    {
        if (empty($this->content)) {
            return null;
        }

        $decoded = json_decode($this->content, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            return null;
        }

        return $decoded;
    }

    /**
     * Parse content as card data (for card generation responses).
     * Returns structured card data or null if parsing fails.
     */
    public function toCardData(): ?array
    {
        $data = $this->toJsonArray();

        if (!$data) {
            return null;
        }

        // Validate expected card fields
        $required = ['name', 'description', 'rarity'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                return null;
            }
        }

        return $data;
    }

    /**
     * Get a masked/truncated version of content for display
     */
    public function getContentPreview(int $length = 200): string
    {
        if (strlen($this->content) <= $length) {
            return $this->content;
        }

        return substr($this->content, 0, $length) . '...';
    }
}
