<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use App\Models\WebsiteSetting;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;

class PdfSettings extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-printer';

    protected static ?string $navigationLabel = 'PDF Settings';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 10;

    protected static string $view = 'filament.pages.pdf-settings';

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
            'card_pdf_background' => WebsiteSetting::get('card_pdf_background'),
            'card_pdf_overlay'    => WebsiteSetting::get('card_pdf_overlay', 'dark'),
        ]);
    }

    /**
     * Define the settings form.
     */
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Card Print Background')
                    ->description('Upload a default background image applied to all printed card PDFs. Users may override this with their own background in their profile.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        FileUpload::make('card_pdf_background')
                            ->label('Default Card Background Image')
                            ->disk('public')
                            ->directory('pdf-backgrounds')
                            ->image()
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('Recommended: match your card dimensions. Falls back to the built-in card back image if not set.')
                            ->columnSpanFull(),
                        Select::make('card_pdf_overlay')
                            ->label('Default Overlay Style')
                            ->options([
                                'dark'  => 'Dark — dark overlay, light text (best for light backgrounds)',
                                'light' => 'Light — light overlay, dark text (best for light backgrounds)',
                            ])
                            ->default('dark')
                            ->required()
                            ->helperText('Controls the overlay tint and text colours applied over the card background. Decks can override this individually.')
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

        WebsiteSetting::updateOrCreate(
            ['key' => 'card_pdf_background'],
            [
                'value'       => $data['card_pdf_background'] ?? null,
                'type'        => 'text',
                'group'       => 'general',
                'label'       => 'Card PDF Background',
                'description' => 'System-wide default background image for printed card PDFs.',
                'order'       => 1,
            ]
        );

        WebsiteSetting::updateOrCreate(
            ['key' => 'card_pdf_overlay'],
            [
                'value'       => $data['card_pdf_overlay'] ?? 'dark',
                'type'        => 'text',
                'group'       => 'general',
                'label'       => 'Card PDF Overlay Style',
                'description' => 'System-wide default overlay style (dark or light) for printed card PDFs.',
                'order'       => 2,
            ]
        );

        Notification::make()
            ->title('PDF settings saved successfully.')
            ->success()
            ->send();
    }

    /**
     * Get the header actions.
     */
    protected function getHeaderActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Settings')
                ->icon('heroicon-o-check')
                ->color('primary')
                ->action('save'),
        ];
    }
}
