<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use App\Models\PhysicalCard;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CardApproved extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public PhysicalCard $card
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Card Listing Has Been Approved!')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your card listing has been approved.')
            ->line('**Card:** ' . $this->card->title)
            ->line('**Condition:** ' . $this->card->condition)
            ->line('**Price:** ' . number_format($this->card->price_per_unit, 2) . ' ' . $this->card->currency)
            ->line('Your card is now active and visible to potential buyers.')
            ->action('View Listing', url('/admin/physical-cards/' . $this->card->id . '/edit'))
            ->line('Thank you for using our marketplace!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'card_id' => $this->card->id,
            'card_title' => $this->card->title,
            'action' => 'approved',
        ];
    }
}
