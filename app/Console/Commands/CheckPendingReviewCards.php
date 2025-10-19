<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Console\Commands;

use App\Models\PhysicalCard;
use App\Models\User;
use App\Notifications\PendingReviewEscalation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CheckPendingReviewCards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cards:check-pending-reviews';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for pending review cards waiting more than 48 hours and send escalation notifications';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Checking for pending review cards older than 48 hours...');

        // Find cards in under_review status for more than 48 hours
        $threshold = now()->subHours(48);

        $pendingCards = PhysicalCard::where('status', PhysicalCard::STATUS_UNDER_REVIEW)
            ->where('created_at', '<=', $threshold)
            ->with('user')
            ->get();

        if ($pendingCards->isEmpty()) {
            $this->info('No pending review cards found older than 48 hours.');
            return self::SUCCESS;
        }

        $this->info("Found {$pendingCards->count()} card(s) requiring escalation.");

        // Get all supervisors
        $supervisors = User::where('supervisor', true)->get();

        if ($supervisors->isEmpty()) {
            $this->warn('No supervisors found to send notifications to!');
            Log::warning('CheckPendingReviewCards: No supervisors available for escalation notifications');
            return self::FAILURE;
        }

        $this->info("Sending notifications to {$supervisors->count()} supervisor(s)...");

        // Send notification for each pending card
        foreach ($pendingCards as $card) {
            $hoursWaiting = (int) $card->created_at->diffInHours(now());

            $this->line("  - Card #{$card->id}: {$card->title} (waiting {$hoursWaiting}h)");

            try {
                Notification::send(
                    $supervisors,
                    new PendingReviewEscalation($card, $hoursWaiting)
                );

                Log::info("Escalation notification sent for card #{$card->id}", [
                    'card_id' => $card->id,
                    'hours_waiting' => $hoursWaiting,
                    'supervisors_notified' => $supervisors->count(),
                ]);
            } catch (\Exception $e) {
                $this->error("  Failed to send notification for card #{$card->id}: {$e->getMessage()}");
                Log::error("Failed to send escalation notification for card #{$card->id}", [
                    'error' => $e->getMessage(),
                    'card_id' => $card->id,
                ]);
            }
        }

        $this->info('✓ Escalation check completed successfully.');

        return self::SUCCESS;
    }
}
