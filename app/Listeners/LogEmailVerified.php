<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LogEmailVerified
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Verified $event): void
    {
        UserActivityLog::create([
            'user_id' => $event->user->id,
            'event_type' => 'email_verified',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
