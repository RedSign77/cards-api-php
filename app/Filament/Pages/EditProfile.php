<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages;

use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage as BaseEditProfilePage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

class EditProfile extends BaseEditProfilePage
{
    public function form(Form $form): Form
    {
        return $form
            ->schema([
                $this->getAvatarFormComponent(),
                $this->getNameFormComponent(),
                $this->getEmailFormComponent(),
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
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }
}
