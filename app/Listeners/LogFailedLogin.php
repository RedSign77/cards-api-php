<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Failed;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LogFailedLogin
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Failed $event): void
    {
        // Only log if we have a user (authenticated user attempting with wrong credentials)
        if ($event->user) {
            UserActivityLog::create([
                'user_id' => $event->user->id,
                'event_type' => 'failed_login',
                'ip_address' => $this->request->ip(),
                'user_agent' => $this->request->userAgent(),
                'created_at' => now(),
            ]);
        }
    }
}
