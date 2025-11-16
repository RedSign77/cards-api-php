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

class OrderStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
        public string $oldStatus,
        public string $newStatus,
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
        $statusLabel = ucwords(str_replace('_', ' ', $this->newStatus));
        $message = (new MailMessage)
            ->subject('Order Status Updated - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Your order status has been updated.');

        $message->line('**Order Number:** ' . $this->order->order_number);
        $message->line('**New Status:** ' . $statusLabel);

        if ($this->recipient === 'buyer') {
            switch ($this->newStatus) {
                case 'packing':
                    $message->line('The seller is preparing your order for shipment.');
                    break;
                case 'paid':
                    $message->line('Your payment has been confirmed. The seller will ship your order soon.');
                    break;
                case 'shipped':
                    $message->line('Your order has been shipped! You should receive it within the estimated delivery time.');
                    $message->line('Please confirm receipt when you receive the package.');
                    break;
                case 'delivered':
                    $message->line('Your order has been marked as delivered.');
                    $message->line('Please confirm receipt to complete the transaction.');
                    break;
                case 'completed':
                    $message->line('Your order has been completed. Thank you for your purchase!');
                    break;
                case 'cancelled':
                    $message->line('Your order has been cancelled. If you have any questions, please contact the seller.');
                    break;
            }
        } else {
            switch ($this->newStatus) {
                case 'packing':
                    $message->line('You\'ve marked this order as being packed. Don\'t forget to coordinate payment and shipping.');
                    break;
                case 'paid':
                    $message->line('Payment has been confirmed. Please prepare the order for shipment.');
                    break;
                case 'shipped':
                    $message->line('You\'ve marked this order as shipped. The buyer has been notified.');
                    break;
                case 'delivered':
                    $message->line('The order has been marked as delivered. Please confirm when appropriate.');
                    break;
                case 'completed':
                    $message->line('The order has been completed successfully. Thank you for being a seller!');
                    break;
                case 'cancelled':
                    $message->line('The order has been cancelled.');
                    break;
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
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'recipient' => $this->recipient,
            'action' => 'status_changed',
        ];
    }
}
