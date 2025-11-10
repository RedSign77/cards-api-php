<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Hash;
use Filament\Actions\Action;
use Illuminate\Validation\Rule;

class EditProfile extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $view = 'filament.pages.edit-profile';

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $slug = 'edit-profile';

    protected static ?string $title = 'Edit Profile';

    protected static bool $shouldRegisterNavigation = false;

    public ?array $data = [];

    public function mount(): void
    {
        $user = auth()->user();

        $this->form->fill([
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
            'location' => $user->location,
            'phone' => $user->phone,
            'website' => $user->website,
            'bio' => $user->bio,
            'currency_code' => $user->currency_code ?? 'USD',
            'seller_location' => $user->seller_location,
            'shipping_options' => $user->shipping_options,
            'shipping_price' => $user->shipping_price,
            'shipping_currency' => $user->shipping_currency ?? 'USD',
            'delivery_time' => $user->delivery_time,
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        $user = auth()->user();

        // Handle password separately
        if (filled($data['password'] ?? null)) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        // Remove password confirmation field
        if (isset($data['passwordConfirmation'])) {
            unset($data['passwordConfirmation']);
        }

        $user->update($data);

        Notification::make()
            ->success()
            ->title('Profile updated successfully')
            ->send();
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Save Changes')
                ->submit('save'),
        ];
    }

    protected function hasFullWidthFormActions(): bool
    {
        return false;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Profile Information')
                    ->schema([
                        FileUpload::make('avatar_url')
                            ->label('Profile Picture')
                            ->avatar()
                            ->imageEditor()
                            ->disk('public')
                            ->directory('avatars')
                            ->visibility('public')
                            ->maxSize(2048),
                        TextInput::make('name')
                            ->label('Name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->rule(fn () => Rule::unique('users', 'email')->ignore(auth()->id())),
                        TextInput::make('location')
                            ->label('Location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('City, Country'),
                        TextInput::make('phone')
                            ->label('Phone')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('+1 (555) 123-4567'),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->maxLength(255)
                            ->placeholder('https://example.com'),
                        Textarea::make('bio')
                            ->label('Bio')
                            ->rows(4)
                            ->maxLength(1000)
                            ->placeholder('Tell us about yourself...')
                            ->columnSpanFull(),
                        Select::make('currency_code')
                            ->label('Preferred Currency')
                            ->options(function () {
                                return \App\Models\Currency::active()
                                    ->orderBy('sort_order')
                                    ->get()
                                    ->mapWithKeys(fn ($currency) => [
                                        $currency->code => "{$currency->code} - {$currency->name} ({$currency->symbol})"
                                    ])
                                    ->toArray();
                            })
                            ->default('USD')
                            ->required()
                            ->helperText('Prices will be displayed in your preferred currency')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Marketplace Settings')
                    ->schema([
                        TextInput::make('seller_location')
                            ->label('Seller Location')
                            ->maxLength(255)
                            ->placeholder('Shipping from location'),
                        TextInput::make('delivery_time')
                            ->label('Delivery Time')
                            ->maxLength(255)
                            ->placeholder('e.g., 3-5 business days'),
                        Textarea::make('shipping_options')
                            ->label('Shipping Options')
                            ->rows(3)
                            ->placeholder('Describe available shipping methods...')
                            ->columnSpanFull(),
                        TextInput::make('shipping_price')
                            ->label('Shipping Price')
                            ->numeric()
                            ->minValue(0)
                            ->step(0.01)
                            ->placeholder('Base shipping price'),
                        Select::make('shipping_currency')
                            ->label('Shipping Currency')
                            ->options([
                                'USD' => 'USD - US Dollar',
                                'EUR' => 'EUR - Euro',
                                'GBP' => 'GBP - British Pound',
                                'JPY' => 'JPY - Japanese Yen',
                                'CAD' => 'CAD - Canadian Dollar',
                                'AUD' => 'AUD - Australian Dollar',
                                'HUF' => 'HUF - Hungarian Forint',
                            ])
                            ->default('USD')
                            ->required(),
                    ])
                    ->columns(2)
                    ->visible(fn () => auth()->user()?->isSupervisor() ?? false),

                Section::make('Change Password')
                    ->schema([
                        TextInput::make('password')
                            ->label('New Password')
                            ->password()
                            ->revealable()
                            ->minLength(12)
                            ->same('passwordConfirmation')
                            ->dehydrated(fn ($state) => filled($state)),
                        TextInput::make('passwordConfirmation')
                            ->label('Confirm New Password')
                            ->password()
                            ->revealable()
                            ->dehydrated(false),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }
}
