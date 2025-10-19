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

class PendingReviewEscalation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public PhysicalCard $card,
        public int $hoursWaiting
    ) {
    }

    public function via(object $notifiable): array
    {
        if (! config('mail.enabled')) {
            return ['database'];
        }

        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('⚠ Pending Review Card Requires Attention')
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A critical card listing has been waiting for review for **' . $this->hoursWaiting . ' hours**.')
            ->line('**Card:** ' . $this->card->title)
            ->line('**Seller:** ' . $this->card->user->name)
            ->line('**Submitted:** ' . $this->card->created_at->format('Y-m-d H:i:s') . ' (' . $this->card->created_at->diffForHumans() . ')')
            ->line('**Price:** ' . number_format($this->card->price_per_unit, 2) . ' ' . $this->card->currency)
            ->line('**Evaluation Flags:** ' . (count($this->card->evaluation_flags ?? []) > 0 ? implode(', ', array_map(fn($f) => str_replace('_', ' ', ucwords($f, '_')), $this->card->evaluation_flags)) : 'None'))
            ->action('Review Card Now', url('/admin/pending-review-cards'))
            ->line('Please review this listing as soon as possible to avoid delays for the seller.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'card_id' => $this->card->id,
            'card_title' => $this->card->title,
            'seller_name' => $this->card->user->name,
            'hours_waiting' => $this->hoursWaiting,
            'submitted_at' => $this->card->created_at->toDateTimeString(),
            'action_url' => url('/admin/pending-review-cards'),
        ];
    }
}
