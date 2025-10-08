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

Schedule::command('activity-logs:cleanup')->daily();
