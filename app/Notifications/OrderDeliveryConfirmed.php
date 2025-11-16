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

class OrderDeliveryConfirmed extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
        public string $confirmedBy, // 'buyer' or 'seller'
        public string $recipient // 'buyer' or 'seller'
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
        $message = (new MailMessage)
            ->subject('Delivery Confirmation - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('**Order Number:** ' . $this->order->order_number);

        if ($this->confirmedBy === $this->recipient) {
            // Confirmation acknowledgment to the person who confirmed
            $message->line('Thank you for confirming the delivery of this order.');

            if ($this->order->status === 'completed') {
                $message->line('The order has been completed successfully!');
            } else {
                $otherParty = $this->recipient === 'buyer' ? 'seller' : 'buyer';
                $message->line('Waiting for the ' . $otherParty . ' to also confirm delivery to complete the transaction.');
            }
        } else {
            // Notification to the other party
            $confirmer = $this->confirmedBy === 'buyer' ? 'buyer' : 'seller';
            $message->line('The ' . $confirmer . ' has confirmed delivery of this order.');

            if ($this->order->status === 'completed') {
                $message->line('The order has been completed successfully!');
                $message->line('Thank you for ' . ($this->recipient === 'seller' ? 'selling' : 'purchasing') . ' on our marketplace!');
            } else {
                $message->line('Please confirm delivery on your end to complete the transaction.');
            }
        }

        $actionUrl = $this->recipient === 'buyer'
            ? url('/admin/pages/my-orders')
            : url('/admin/pages/my-sales');

        $message->action('View Order', $actionUrl);

        return $message;
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
            'confirmed_by' => $this->confirmedBy,
            'recipient' => $this->recipient,
            'action' => 'delivery_confirmed',
        ];
    }
}
