<?php

namespace App\Notifications;

use App\Models\Game;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewGameAdded extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Game $game
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Game Added - ' . config('app.name'))
            ->greeting('New Game Created!')
            ->line('A new game has been added to the platform.')
            ->line('**Game Details:**')
            ->line('Game Name: ' . $this->game->name)
            ->line('Created by: ' . $this->game->creator->name)
            ->line('Creator Email: ' . $this->game->creator->email)
            ->line('Created at: ' . $this->game->created_at->format('Y-m-d H:i:s'))
            ->action('View Game in Admin', url('/admin/games/' . $this->game->id . '/edit'))
            ->line('Thank you for monitoring the platform!');
    }
}
