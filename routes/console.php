<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\UserActivityLog;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('activity-logs:cleanup', function () {
    $weekAgo = now()->subWeek();
    $deleted = UserActivityLog::where('created_at', '<', $weekAgo)->delete();

    $this->info("Deleted {$deleted} old activity log entries (older than one week).");
})->purpose('Clean up old user activity logs (older than one week)');

// Schedule: Run queue worker every minute to process pending jobs
Schedule::command('queue:work', ['database', '--stop-when-empty', '--tries=3', '--max-time=50'])
    ->everyMinute()
    ->withoutOverlapping();

// Schedule: Clean up old activity logs daily
Schedule::command('activity-logs:cleanup')->daily();

// Schedule: Clean up old completed jobs (older than 30 days) weekly
Schedule::call(function () {
    $deleted = \App\Models\CompletedJob::where('completed_at', '<', now()->subDays(30))->delete();
    \Log::info("Cleaned up {$deleted} old completed jobs (older than 30 days)");
})->weekly()->sundays()->at('02:00');
