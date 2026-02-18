<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 *
 * AI Service Configuration
 * Settings can be overridden via Filament Admin > AI Management > AI Settings
 */

return [

    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | The default AI provider to use. Can be overridden via AiSetting model.
    | Supported: "openai", "anthropic"
    |
    */
    'default_provider' => env('AI_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | Request Settings
    |--------------------------------------------------------------------------
    */
    'retry_attempts' => env('AI_RETRY_ATTEMPTS', 3),
    'retry_delay_ms' => env('AI_RETRY_DELAY_MS', 1000),
    'timeout_seconds' => env('AI_TIMEOUT_SECONDS', 60),

    /*
    |--------------------------------------------------------------------------
    | Default Parameters
    |--------------------------------------------------------------------------
    */
    'default_max_tokens' => env('AI_MAX_TOKENS', 2048),
    'default_temperature' => env('AI_TEMPERATURE', 0.7),

    /*
    |--------------------------------------------------------------------------
    | Providers Configuration
    |--------------------------------------------------------------------------
    */
    'providers' => [

        'openai' => [
            'api_key_env' => 'OPENAI_API_KEY',
            'base_url' => 'https://api.openai.com/v1',
            'default_model' => 'gpt-4o',
            'models' => [
                'gpt-4o' => 'GPT-4o',
                'gpt-4o-mini' => 'GPT-4o Mini',
                'gpt-4-turbo' => 'GPT-4 Turbo',
                'gpt-3.5-turbo' => 'GPT-3.5 Turbo',
            ],
            // Cost per 1k tokens in USD (approximate)
            'cost_per_1k_input' => [
                'gpt-4o' => 0.0025,
                'gpt-4o-mini' => 0.00015,
                'gpt-4-turbo' => 0.01,
                'gpt-3.5-turbo' => 0.0005,
            ],
            'cost_per_1k_output' => [
                'gpt-4o' => 0.01,
                'gpt-4o-mini' => 0.0006,
                'gpt-4-turbo' => 0.03,
                'gpt-3.5-turbo' => 0.0015,
            ],
        ],

        'anthropic' => [
            'api_key_env' => 'ANTHROPIC_API_KEY',
            'base_url' => 'https://api.anthropic.com/v1',
            'default_model' => 'claude-opus-4-6',
            'models' => [
                'claude-opus-4-6' => 'Claude Opus 4.6',
                'claude-sonnet-4-6' => 'Claude Sonnet 4.6',
                'claude-haiku-4-5-20251001' => 'Claude Haiku 4.5',
            ],
            // Cost per 1k tokens in USD (approximate)
            'cost_per_1k_input' => [
                'claude-opus-4-6' => 0.015,
                'claude-sonnet-4-6' => 0.003,
                'claude-haiku-4-5-20251001' => 0.00025,
            ],
            'cost_per_1k_output' => [
                'claude-opus-4-6' => 0.075,
                'claude-sonnet-4-6' => 0.015,
                'claude-haiku-4-5-20251001' => 0.00125,
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Logging
    |--------------------------------------------------------------------------
    */
    'log_requests' => env('AI_LOG_REQUESTS', true),
    'log_cleanup_days' => env('AI_LOG_CLEANUP_DAYS', 30),

];
