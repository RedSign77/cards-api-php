<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserRegistered extends Notification implements ShouldQueue
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
            ->subject('New User Registration - ' . config('app.name'))
            ->greeting('New User Registered!')
            ->line('A new user has registered on the platform.')
            ->line('**User Details:**')
            ->line('Name: ' . $this->user->name)
            ->line('Email: ' . $this->user->email)
            ->line('Registered at: ' . $this->user->created_at->format('Y-m-d H:i:s'))
            ->action('View User in Admin', url('/admin/users/' . $this->user->id . '/edit'))
            ->line('Thank you for monitoring the platform!');
    }
}
