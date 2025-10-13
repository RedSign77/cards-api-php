<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Pages\Auth\Register as BaseRegister;
use App\Rules\Recaptcha;
use Filament\Notifications\Notification;
use DanHarrin\LivewireRateLimiting\Exceptions\TooManyRequestsException;
use Illuminate\Auth\Events\Registered;
use Filament\Actions\Action;

class Register extends BaseRegister
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                        Hidden::make('g-recaptcha-response')
                            ->default('')
                            ->reactive()
                            ->rules([new Recaptcha('register')])
                            ->validationAttribute('reCAPTCHA'),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel());
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->required()
            ->minLength(12)
            ->same('passwordConfirmation')
            ->revealable();
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
            ->password()
            ->required()
            ->revealable();
    }

    public function getView(): string
    {
        return 'filament.pages.auth.register';
    }

    protected function getLoginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Sign in')
            ->url(filament()->getLoginUrl());
    }

    public function register(): ?\Filament\Http\Responses\Auth\Contracts\RegistrationResponse
    {
        try {
            $this->rateLimit(2);
        } catch (TooManyRequestsException $exception) {
            Notification::make()
                ->title(__('filament-panels::pages/auth/register.notifications.throttled.title', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]))
                ->body(array_key_exists('body', __('filament-panels::pages/auth/register.notifications.throttled') ?: []) ? __('filament-panels::pages/auth/register.notifications.throttled.body', [
                    'seconds' => $exception->secondsUntilAvailable,
                    'minutes' => ceil($exception->secondsUntilAvailable / 60),
                ]) : null)
                ->danger()
                ->send();

            return null;
        }

        $data = $this->form->getState();

        $user = $this->getUserModel()::create($data);

        event(new Registered($user));

        // Redirect to registration page with success message instead of logging in
        Notification::make()
            ->title('Registration Successful!')
            ->body('Please check your email to verify your account. After email verification, a supervisor will need to approve your account before you can access the system.')
            ->success()
            ->persistent()
            ->send();

        $this->redirect(route('filament.admin.auth.register'));

        return null;
    }
}
