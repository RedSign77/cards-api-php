<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LogUserLogout
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Logout $event): void
    {
        // Check if there's a recent logout entry (within last 5 seconds) to prevent duplicates
        $recentLog = UserActivityLog::where('user_id', $event->user->id)
            ->where('event_type', 'logout')
            ->where('created_at', '>=', now()->subSeconds(5))
            ->first();

        if (!$recentLog) {
            UserActivityLog::create([
                'user_id' => $event->user->id,
                'event_type' => 'logout',
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
                'created_at' => now(),
            ]);
        }
    }
}
