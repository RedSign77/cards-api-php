<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Console\Commands;

use App\Models\CartItem;
use Illuminate\Console\Command;

class CleanupExpiredCartReservations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:cleanup-expired-reservations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove cart items with expired reservations (older than 1 hour)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Cleaning up expired cart reservations...');

        // Delete cart items with expired reservations
        $deletedCount = CartItem::expiredReservations()->delete();

        if ($deletedCount > 0) {
            $this->info("Deleted {$deletedCount} expired cart item(s)");
        } else {
            $this->info('No expired cart items found');
        }

        return Command::SUCCESS;
    }
}
