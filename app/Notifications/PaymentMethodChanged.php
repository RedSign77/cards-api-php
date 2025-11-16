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

class PaymentMethodChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        public Order $order,
        public string $oldMethod,
        public string $newMethod
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
        $oldMethodLabel = match($this->oldMethod) {
            'paypal' => 'PayPal',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer',
            default => $this->oldMethod,
        };

        $newMethodLabel = match($this->newMethod) {
            'paypal' => 'PayPal',
            'check' => 'Check',
            'bank_transfer' => 'Bank Transfer',
            default => $this->newMethod,
        };

        return (new MailMessage)
            ->subject('Payment Method Changed - ' . $this->order->order_number)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('The payment method for your order has been updated.')
            ->line('**Order Number:** ' . $this->order->order_number)
            ->line('**Previous Method:** ' . $oldMethodLabel)
            ->line('**New Method:** ' . $newMethodLabel)
            ->line('Please check the updated payment instructions for details on how to complete your payment.')
            ->action('View Order Details', url('/admin/pages/my-orders'))
            ->line('If you have any questions, please contact the seller.');
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
            'old_method' => $this->oldMethod,
            'new_method' => $this->newMethod,
            'action' => 'payment_method_changed',
        ];
    }
}
