<?php
/**
 * Webtech-solutions 2025, All rights reserved.
 */

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class LogUserRegistered
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function handle(Registered $event): void
    {
        UserActivityLog::create([
            'user_id' => $event->user->id,
            'event_type' => 'registered',
            'ip_address' => $this->request->ip(),
            'user_agent' => $this->request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
