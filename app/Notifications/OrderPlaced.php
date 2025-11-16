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

class OrderPlaced extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
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
        $currencySymbol = $this->order->currency === 'USD' ? '$' : $this->order->currency . ' ';
        $total = $currencySymbol . number_format($this->order->total, 2);

        if ($this->recipient === 'seller') {
            return (new MailMessage)
                ->subject('New Order Received - ' . $this->order->order_number)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('You have received a new order!')
                ->line('**Order Number:** ' . $this->order->order_number)
                ->line('**Buyer:** ' . $this->order->buyer->name)
                ->line('**Total:** ' . $total)
                ->line('**Items:** ' . $this->order->items->count() . ' item(s)')
                ->line('**Payment Method:** ' . match($this->order->payment_method) {
                    'paypal' => 'PayPal',
                    'check' => 'Check',
                    'bank_transfer' => 'Bank Transfer',
                    default => $this->order->payment_method,
                })
                ->line('Please review the order and coordinate payment with the buyer.')
                ->action('View Order Details', url('/admin/pages/my-sales'))
                ->line('Thank you for selling on our marketplace!');
        } else {
            return (new MailMessage)
                ->subject('Order Confirmation - ' . $this->order->order_number)
                ->greeting('Hello ' . $notifiable->name . '!')
                ->line('Your order has been placed successfully!')
                ->line('**Order Number:** ' . $this->order->order_number)
                ->line('**Seller:** ' . $this->order->seller->name)
                ->line('**Total:** ' . $total)
                ->line('**Items:** ' . $this->order->items->count() . ' item(s)')
                ->line('**Shipping Address:**')
                ->line($this->order->shipping_name)
                ->line($this->order->shipping_address_line1)
                ->line(($this->order->shipping_address_line2 ? $this->order->shipping_address_line2 . ', ' : '') . $this->order->shipping_city)
                ->line($this->order->shipping_postal_code . ', ' . $this->order->shipping_country)
                ->line('The seller will coordinate payment and shipping with you.')
                ->action('Track Your Order', url('/admin/pages/my-orders'))
                ->line('Thank you for your purchase!');
        }
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
            'recipient' => $this->recipient,
            'action' => 'order_placed',
        ];
    }
}
