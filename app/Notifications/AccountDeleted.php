<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;

class AccountDeleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public User $deletedBy)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Deleted - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We are writing to inform you that your account on Cards Forge has been deleted.')
            ->line('**Account Details:**')
            ->line('Email: ' . $notifiable->email)
            ->line('Account deleted by: ' . $this->deletedBy->name)
            ->line('Date: ' . now()->format('F j, Y \a\t g:i A'))
            ->line('If you did not request this deletion or believe this was done in error, please contact us immediately.')
            ->action('Contact Support', url('/'))
            ->line('All your data including games, cards, decks, and card types have been permanently removed from our system.')
            ->line('Thank you for using Cards Forge.');
    }
}
