<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use App\Models\AiSetting;
use App\Services\Ai\AiProxyService;
use Filament\Actions\Action;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class AiSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cpu-chip';

    protected static ?string $navigationLabel = 'AI Settings';

    protected static ?string $navigationGroup = 'AI Management';

    protected static ?int $navigationSort = 1;

    protected static string $view = 'filament.pages.ai-settings';

    public ?array $data = [];

    /**
     * Only supervisors can access this page.
     */
    public static function canAccess(): bool
    {
        return auth()->check() && auth()->user()->isSupervisor();
    }

    /**
     * Load current settings into the form.
     */
    public function mount(): void
    {
        $this->form->fill([
            'ai_provider' => AiSetting::get('ai_provider', 'openai'),
            'ai_api_key' => AiSetting::get('ai_api_key', ''),
            'ai_model' => AiSetting::get('ai_model', 'gpt-4o'),
            'ai_max_tokens' => AiSetting::get('ai_max_tokens', 2048),
            'ai_temperature' => AiSetting::get('ai_temperature', 0.7),
            'ai_system_prompt' => AiSetting::get('ai_system_prompt', config('ai_prompts.card_generation.system', '')),
        ]);
    }

    /**
     * Define the settings form.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Provider Configuration')
                    ->description('Configure the AI service provider and authentication.')
                    ->icon('heroicon-o-cloud')
                    ->schema([
                        Select::make('ai_provider')
                            ->label('AI Provider')
                            ->options([
                                'openai' => 'OpenAI',
                                'anthropic' => 'Anthropic (Claude)',
                            ])
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                // Reset model when provider changes
                                $defaultModels = [
                                    'openai' => 'gpt-4o',
                                    'anthropic' => 'claude-sonnet-4-6',
                                ];
                                $set('ai_model', $defaultModels[$state] ?? 'gpt-4o');
                            }),

                        TextInput::make('ai_api_key')
                            ->label('API Key')
                            ->password()
                            ->revealable()
                            ->placeholder('sk-... or sk-ant-...')
                            ->helperText('Stored encrypted in the database. Leave blank to keep existing key.')
                            ->maxLength(500),

                        Select::make('ai_model')
                            ->label('Model')
                            ->options(function ($get) {
                                $provider = $get('ai_provider') ?? 'openai';
                                return config("ai.providers.{$provider}.models", []);
                            })
                            ->required()
                            ->searchable(),
                    ])
                    ->columns(2),

                Section::make('Generation Parameters')
                    ->description('Control the behavior of AI-generated responses.')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->schema([
                        TextInput::make('ai_max_tokens')
                            ->label('Max Tokens')
                            ->numeric()
                            ->minValue(100)
                            ->maxValue(8192)
                            ->default(2048)
                            ->helperText('Maximum number of tokens in the AI response (100-8192).'),

                        TextInput::make('ai_temperature')
                            ->label('Temperature')
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(2)
                            ->step(0.1)
                            ->default(0.7)
                            ->helperText('Controls randomness: 0 = deterministic, 2 = very random. Recommended: 0.7'),
                    ])
                    ->columns(2),

                Section::make('System Prompt')
                    ->description('The default system prompt for card generation. Defines the AI\'s role and output format.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Textarea::make('ai_system_prompt')
                            ->label('Card Generation System Prompt')
                            ->rows(15)
                            ->helperText('This prompt is sent with every card generation request. It defines the AI\'s domain knowledge and required JSON output format.')
                            ->columnSpanFull(),
                    ]),
            ])
            ->statePath('data');
    }

    /**
     * Save the settings to the database.
     */
    public function save(): void
    {
        $data = $this->form->getState();

        // Only update API key if a new one was provided
        if (!empty($data['ai_api_key'])) {
            $this->saveEncryptedSetting('ai_api_key', $data['ai_api_key']);
        }

        $this->saveSetting('ai_provider', $data['ai_provider'], 'text', 'provider', 'AI Provider', 1);
        $this->saveSetting('ai_model', $data['ai_model'], 'text', 'provider', 'AI Model', 2);
        $this->saveSetting('ai_max_tokens', $data['ai_max_tokens'], 'number', 'parameters', 'Max Tokens', 1);
        $this->saveSetting('ai_temperature', $data['ai_temperature'], 'number', 'parameters', 'Temperature', 2);
        $this->saveSetting('ai_system_prompt', $data['ai_system_prompt'], 'textarea', 'prompts', 'System Prompt', 1);

        Notification::make()
            ->title('Settings saved successfully.')
            ->success()
            ->send();
    }

    /**
     * Test the current AI provider connection.
     */
    public function testConnection(): void
    {
        // Save current form state first before testing
        $data = $this->form->getState();
        if (!empty($data['ai_api_key'])) {
            $this->saveEncryptedSetting('ai_api_key', $data['ai_api_key']);
        }
        $this->saveSetting('ai_provider', $data['ai_provider'], 'text', 'provider', 'AI Provider', 1);
        $this->saveSetting('ai_model', $data['ai_model'], 'text', 'provider', 'AI Model', 2);

        try {
            $service = new AiProxyService();
            $result = $service->testConnection();

            if ($result['success']) {
                Notification::make()
                    ->title('Connection successful!')
                    ->body('The AI provider responded correctly.')
                    ->success()
                    ->send();
            } elseif ($result['status'] === 'rate_limited') {
                Notification::make()
                    ->title('API key is valid (rate limited)')
                    ->body($result['message'])
                    ->warning()
                    ->send();
            } else {
                Notification::make()
                    ->title('Connection failed')
                    ->body($result['message'])
                    ->danger()
                    ->send();
            }
        } catch (\Exception $e) {
            Notification::make()
                ->title('Connection error')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    /**
     * Get the header actions (Save + Test Connection buttons).
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('testConnection')
                ->label('Test Connection')
                ->icon('heroicon-o-signal')
                ->color('gray')
                ->action('testConnection'),

            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('save'),
        ];
    }

    /**
     * Save a plain-text setting.
     */
    private function saveSetting(string $key, mixed $value, string $type, string $group, string $label, int $order): void
    {
        AiSetting::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'type' => $type,
                'group' => $group,
                'label' => $label,
                'order' => $order,
            ]
        );
    }

    /**
     * Save an encrypted setting.
     */
    private function saveEncryptedSetting(string $key, string $value): void
    {
        $existing = AiSetting::where('key', $key)->first();

        if ($existing) {
            // Directly update encrypted value using the mutator
            $existing->type = 'encrypted';
            $existing->value = $value;
            $existing->save();
        } else {
            AiSetting::create([
                'key' => $key,
                'value' => $value,
                'type' => 'encrypted',
                'group' => 'provider',
                'label' => 'API Key',
                'order' => 3,
            ]);
        }
    }
}
