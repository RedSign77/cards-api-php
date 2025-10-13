<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Filament\Pages\Auth;

use Filament\Pages\Auth\PasswordReset\RequestPasswordReset as BaseRequestPasswordReset;
use Filament\Actions\Action;

class RequestPasswordReset extends BaseRequestPasswordReset
{
    public function getView(): string
    {
        return 'filament.pages.auth.request-password-reset';
    }

    protected function getLoginAction(): Action
    {
        return Action::make('login')
            ->link()
            ->label('Back to Login')
            ->url(filament()->getLoginUrl());
    }
}
