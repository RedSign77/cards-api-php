<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Console\Commands;

use App\Models\CompletedJob;
use App\Models\SupervisorActivityLog;
use App\Models\UserActivityLog;
use App\Models\WebsiteSetting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupOldLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'logs:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete old logs based on website settings retention periods';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting log cleanup based on configured retention periods...');
        $this->newLine();

        $totalDeleted = 0;

        // Clean up User Activity Logs
        $userActivityDays = (int) WebsiteSetting::get('logs.user_activity_retention_days', 30);
        $userActivityCount = $this->cleanupTable(
            UserActivityLog::class,
            'User Activity Logs',
            $userActivityDays
        );
        $totalDeleted += $userActivityCount;

        // Clean up Supervisor Activity Logs
        $supervisorActivityDays = (int) WebsiteSetting::get('logs.supervisor_activity_retention_days', 90);
        $supervisorActivityCount = $this->cleanupTable(
            SupervisorActivityLog::class,
            'Supervisor Activity Logs',
            $supervisorActivityDays
        );
        $totalDeleted += $supervisorActivityCount;

        // Clean up Completed Jobs (uses completed_at column)
        $completedJobsDays = (int) WebsiteSetting::get('logs.completed_jobs_retention_days', 7);
        $completedJobsCount = $this->cleanupCompletedJobs($completedJobsDays);
        $totalDeleted += $completedJobsCount;

        // Clean up Sessions
        $sessionsDays = (int) WebsiteSetting::get('logs.sessions_retention_days', 7);
        $sessionsCount = $this->cleanupSessions($sessionsDays);
        $totalDeleted += $sessionsCount;

        // Clean up Card Audit Logs (if table exists)
        $cardAuditDays = (int) WebsiteSetting::get('logs.card_audit_retention_days', 180);
        $cardAuditCount = $this->cleanupCardAuditLogs($cardAuditDays);
        $totalDeleted += $cardAuditCount;

        $this->newLine();
        $this->info("✓ Log cleanup completed!");
        $this->info("Total records deleted: {$totalDeleted}");

        return Command::SUCCESS;
    }

    /**
     * Clean up records from a model table
     */
    protected function cleanupTable(string $modelClass, string $label, int $days): int
    {
        $cutoffDate = now()->subDays($days);

        $this->line("Cleaning {$label} (keeping last {$days} days)...");

        $count = $modelClass::where('created_at', '<', $cutoffDate)->delete();

        if ($count > 0) {
            $this->info("  ✓ Deleted {$count} {$label} records");
        } else {
            $this->comment("  ℹ No old {$label} records to delete");
        }

        return $count;
    }

    /**
     * Clean up completed jobs
     */
    protected function cleanupCompletedJobs(int $days): int
    {
        $cutoffDate = now()->subDays($days);

        $this->line("Cleaning Completed Jobs (keeping last {$days} days)...");

        $count = CompletedJob::where('completed_at', '<', $cutoffDate)->delete();

        if ($count > 0) {
            $this->info("  ✓ Deleted {$count} Completed Job records");
        } else {
            $this->comment("  ℹ No old Completed Job records to delete");
        }

        return $count;
    }

    /**
     * Clean up old sessions
     */
    protected function cleanupSessions(int $days): int
    {
        $cutoffTimestamp = now()->subDays($days)->timestamp;

        $this->line("Cleaning Sessions (keeping last {$days} days)...");

        try {
            $count = DB::table('sessions')
                ->where('last_activity', '<', $cutoffTimestamp)
                ->delete();

            if ($count > 0) {
                $this->info("  ✓ Deleted {$count} Session records");
            } else {
                $this->comment("  ℹ No old Session records to delete");
            }

            return $count;
        } catch (\Exception $e) {
            $this->warn("  ⚠ Could not clean sessions: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Clean up card audit logs (if table exists)
     */
    protected function cleanupCardAuditLogs(int $days): int
    {
        $cutoffDate = now()->subDays($days);

        $this->line("Cleaning Card Audit Logs (keeping last {$days} days)...");

        try {
            // Check if table exists
            if (!DB::getSchemaBuilder()->hasTable('card_audit_logs')) {
                $this->comment("  ℹ Card Audit Logs table does not exist, skipping");
                return 0;
            }

            $count = DB::table('card_audit_logs')
                ->where('created_at', '<', $cutoffDate)
                ->delete();

            if ($count > 0) {
                $this->info("  ✓ Deleted {$count} Card Audit Log records");
            } else {
                $this->comment("  ℹ No old Card Audit Log records to delete");
            }

            return $count;
        } catch (\Exception $e) {
            $this->warn("  ⚠ Could not clean card audit logs: " . $e->getMessage());
            return 0;
        }
    }
}
