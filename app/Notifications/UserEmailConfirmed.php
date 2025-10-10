<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserEmailConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $user
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('User Email Confirmed - Action Required - ' . config('app.name'))
            ->greeting('User Email Confirmed!')
            ->line('A user has confirmed their email address and is awaiting approval.')
            ->line('**User Details:**')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Email verified at: ' . $this->user->email_verified_at?->format('Y-m-d H:i:s'))
            ->action('Approve User in Admin', url('/admin/users/' . $this->user->id . '/edit'))
            ->line('Please review and approve this user to grant them access to the platform.');
    }
}
