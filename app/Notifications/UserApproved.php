<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserApproved extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public User $approvedBy
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Account Has Been Approved - ' . config('app.name'))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('Great news! Your account has been approved by a supervisor.')
            ->line('You now have full access to the platform and can start using all features.')
            ->action('Login to Your Account', url('/admin/login'))
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('Welcome aboard!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'action' => 'account_approved',
            'approved_by' => $this->approvedBy->name,
            'message' => 'Your account has been approved by a supervisor.',
        ];
    }
}
