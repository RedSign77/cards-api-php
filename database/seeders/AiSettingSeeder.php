<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace Database\Seeders;

use App\Models\AiSetting;
use Illuminate\Database\Seeder;

class AiSettingSeeder extends Seeder
{
    /**
     * Seed default AI settings.
     */
    public function run(): void
    {
        $defaults = [
            [
                'key' => 'ai_provider',
                'value' => 'openai',
                'type' => 'text',
                'group' => 'provider',
                'label' => 'AI Provider',
                'description' => 'The AI service provider to use (openai or anthropic).',
                'order' => 1,
            ],
            [
                'key' => 'ai_model',
                'value' => 'gpt-4o',
                'type' => 'text',
                'group' => 'provider',
                'label' => 'AI Model',
                'description' => 'The model to use for AI requests.',
                'order' => 2,
            ],
            [
                'key' => 'ai_api_key',
                'value' => '',
                'type' => 'encrypted',
                'group' => 'provider',
                'label' => 'API Key',
                'description' => 'The API key for the configured AI provider. Stored encrypted.',
                'order' => 3,
            ],
            [
                'key' => 'ai_max_tokens',
                'value' => '2048',
                'type' => 'number',
                'group' => 'parameters',
                'label' => 'Max Tokens',
                'description' => 'Maximum number of tokens in the AI response.',
                'order' => 1,
            ],
            [
                'key' => 'ai_temperature',
                'value' => '0.7',
                'type' => 'number',
                'group' => 'parameters',
                'label' => 'Temperature',
                'description' => 'Controls randomness of AI output (0.0 - 2.0).',
                'order' => 2,
            ],
            [
                'key' => 'ai_system_prompt',
                'value' => config('ai_prompts.card_generation.system', ''),
                'type' => 'textarea',
                'group' => 'prompts',
                'label' => 'Card Generation System Prompt',
                'description' => 'The system prompt sent with every card generation AI request.',
                'order' => 1,
            ],
        ];

        foreach ($defaults as $setting) {
            AiSetting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('AI settings seeded successfully.');
    }
}
