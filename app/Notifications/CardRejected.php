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

class CardRejected extends Notification implements ShouldQueue
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
            ->subject('Card Listing Review Update')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('We have reviewed your card listing and unfortunately it cannot be approved at this time.')
            ->line('**Card:** ' . $this->card->title)
            ->line('**Reason:**')
            ->line($this->card->rejection_reason)
            ->line('You can edit your listing and resubmit it for review.')
            ->action('Edit Listing', url('/admin/physical-cards/' . $this->card->id . '/edit'))
            ->line('If you have any questions, please contact our support team.');
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
            'action' => 'rejected',
            'reason' => $this->card->rejection_reason,
        ];
    }
}
