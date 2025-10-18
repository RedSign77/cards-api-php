<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Console\Commands;

use App\Models\CompletedJob;
use App\Models\SupervisorActivityLog;
use App\Models\UserActivityLog;
use Illuminate\Console\Command;

class CleanupOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup {--days=20 : Number of days to keep logs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old logs from User Activity Logs, Supervisor Activity Logs, and Completed Jobs';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $cutoffDate = now()->subDays($days);

        $this->info("Deleting logs older than {$days} days (before {$cutoffDate->toDateTimeString()})...");

        // Delete old User Activity Logs
        $userActivityCount = UserActivityLog::where('created_at', '<', $cutoffDate)->delete();
        $this->info("Deleted {$userActivityCount} User Activity Log records");

        // Delete old Supervisor Activity Logs
        $supervisorActivityCount = SupervisorActivityLog::where('created_at', '<', $cutoffDate)->delete();
        $this->info("Deleted {$supervisorActivityCount} Supervisor Activity Log records");

        // Delete old Completed Jobs
        $completedJobsCount = CompletedJob::where('created_at', '<', $cutoffDate)->delete();
        $this->info("Deleted {$completedJobsCount} Completed Job records");

        $totalDeleted = $userActivityCount + $supervisorActivityCount + $completedJobsCount;
        $this->info("Total records deleted: {$totalDeleted}");

        return Command::SUCCESS;
    }
}
