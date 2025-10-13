<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Validation\ValidationException;
use App\Rules\Recaptcha;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Login extends BaseLogin
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                        Hidden::make('g-recaptcha-response')
                            ->default('')
                            ->reactive()
                            ->rules([new Recaptcha('login')])
                            ->validationAttribute('reCAPTCHA'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/login.form.email.label'))
            ->email()
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/login.form.password.label'))
            ->password()
            ->required()
            ->extraInputAttributes(['tabindex' => 2]);
    }

    protected function throwFailureValidationException(): never
    {
        // Get the email from the form data
        $email = $this->form->getState()['email'] ?? null;

        // Try to find the user by email
        $user = $email ? User::where('email', $email)->first() : null;

        // Determine appropriate error message
        $errorMessage = __('filament-panels::pages/auth/login.messages.failed');

        if ($user) {
            // User exists, check their status
            if (is_null($user->email_verified_at)) {
                $errorMessage = 'Your account email has not been verified. Please check your email inbox for the verification link.';
            } elseif (is_null($user->approved_at) && !$user->supervisor) {
                $errorMessage = 'Your account is awaiting supervisor approval. You will receive an email notification once your account is approved.';
            } else {
                // Email is verified and account is approved (or is supervisor), so password must be wrong
                $errorMessage = 'The password you entered is incorrect. Please try again.';
            }
        } else {
            // User doesn't exist
            $errorMessage = 'No account found with this email address. Please check your email or register for a new account.';
        }

        throw ValidationException::withMessages([
            'data.email' => $errorMessage,
        ]);
    }

    public function getView(): string
    {
        return 'filament.pages.auth.login';
    }
}
