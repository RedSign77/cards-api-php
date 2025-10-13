<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use App\Models\CompletedJob;
use Illuminate\Queue\Events\JobProcessed;
use Illuminate\Support\Str;

class LogCompletedJob
{
    /**
     * Handle the event.
     */
    public function handle(JobProcessed $event): void
    {
        try {
            $payload = json_decode($event->job->getRawBody(), true);

            // Extract job class name
            $jobClass = null;
            if (isset($payload['data']['commandName'])) {
                $jobClass = $payload['data']['commandName'];
            } elseif (isset($payload['displayName'])) {
                $jobClass = $payload['displayName'];
            }

            CompletedJob::create([
                'uuid' => $payload['uuid'] ?? Str::uuid(),
                'connection' => $event->connectionName,
                'queue' => $event->job->getQueue(),
                'payload' => $payload,
                'job_class' => $jobClass,
                'completed_at' => now(),
                'attempts' => $payload['attempts'] ?? 1,
                'execution_time' => null, // Will be calculated if needed
            ]);
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the queue processing
            \Log::error('Failed to log completed job: ' . $e->getMessage());
        }
    }
}
