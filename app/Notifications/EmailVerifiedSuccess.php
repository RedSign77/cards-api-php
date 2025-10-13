<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerifiedSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Email Address Verified - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Thank you for verifying your email address.')
            ->line('Your email has been successfully confirmed.')
            ->line('**Next Steps:**')
            ->line('Your account is now awaiting approval from a supervisor. You will receive another email notification once your account has been approved.')
            ->line('After approval, you will be able to access all features of the platform.')
            ->line('This process typically takes 1-2 business days.')
            ->line('Thank you for your patience!');
    }
}
