<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentInfoUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order
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
        $currencySymbol = $this->order->currency === 'USD' ? '$' : $this->order->currency . ' ';
        $total = $currencySymbol . number_format($this->order->total, 2);

        return (new MailMessage)
            ->subject('Payment Instructions Updated - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The seller has updated the payment instructions for your order.')
            ->line('**Order Number:** ' . $this->order->order_number)
            ->line('**Total Amount:** ' . $total)
            ->line('**Payment Instructions:**')
            ->line($this->order->seller_payment_info ?: 'No payment instructions provided yet.')
            ->action('View Order Details', url('/admin/pages/my-orders'))
            ->line('Please complete the payment as instructed by the seller.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'action' => 'payment_info_updated',
        ];
    }
}
