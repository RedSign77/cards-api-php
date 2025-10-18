<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Schedule: Run queue worker every minute to process pending jobs
Schedule::command('queue:work', ['database', '--stop-when-empty', '--tries=3', '--max-time=50'])
    ->everyMinute()
    ->withoutOverlapping();

// Schedule: Clean up old logs daily at 2 AM (User Activity Logs, Supervisor Activity Logs, Completed Jobs older than 20 days)
Schedule::command('logs:cleanup')->dailyAt('02:00');
